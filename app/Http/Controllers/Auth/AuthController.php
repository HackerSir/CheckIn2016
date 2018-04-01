<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Role;
use App\Services\FcuApiService;
use App\Services\LogService;
use App\Services\MailService;
use App\Setting;
use App\Student;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Throttle;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $mailService;

    use AuthenticatesAndRegistersUsers, ThrottlesLogins {
        register as originalRegister;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * @var FcuApiService
     */
    private $fcuApiService;
    /**
     * @var LogService
     */
    private $logService;

    /**
     * Create a new authentication controller instance.
     *
     * @param MailService $mailService
     * @param FcuApiService $fcuApiService
     * @param LogService $logService
     */
    public function __construct(MailService $mailService, FcuApiService $fcuApiService, LogService $logService)
    {
        $this->middleware($this->guestMiddleware(), [
            'except' => [
                'logout',
                'emailConfirm',
                'resendConfirmMailPage',
                'resendConfirmMail',
            ],
        ]);

        $this->middleware('auth', [
            'only' => [
                'resendConfirmMailPage',
                'resendConfirmMail',
            ],
        ]);

        $this->mailService = $mailService;
        $this->fcuApiService = $fcuApiService;
        $this->logService = $logService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $emailRule = ['required', 'email', 'max:255', 'unique:users'];
        //信箱域名白名單
        $domainSetting = Setting::getRaw('AllowedEmailDomains');
        if (!empty(trim($domainSetting))) {
            //切割設定值，每行為一筆
            $domains = preg_split('/$\R?^/m', Setting::getRaw('AllowedEmailDomains'));
            $domainPattern = 'regex:/';
            foreach ($domains as $domain) {
                //不處理空白行
                if (empty(trim($domain))) {
                    continue;
                }
                $domainPattern .= '^[^@]+@' . str_replace('.', '\.', $domain) . '$|';
            }
            //替換pattern結尾字符
            $domainPattern = str_replace_last('|', '/', $domainPattern);
            //追加到驗證規則
            array_push($emailRule, $domainPattern);
        }

        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => $emailRule,
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * 重新包裝註冊方法，以寄送驗證信件
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // 呼叫原始註冊方法
        $result = $this->originalRegister($request);
        /** @var User $user */
        $user = auth()->user();
        $this->generateConfirmCodeAndSendConfirmMail($user);
        // 紀錄註冊時間與IP
        $user->register_at = Carbon::now();
        $user->register_ip = $request->ip();
        $user->save();
        // 賦予第一位註冊的人管理員權限
        if (User::count() == 1) {
            $admin = Role::where('name', '=', 'Admin')->first();
            $user->attachRole($admin);
        }

        // 回傳結果
        return $result->with('global', '註冊完成，請至信箱收取驗證信件。');
    }

    /**
     * 驗證信箱
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $confirmCode
     * @return \Illuminate\Http\Response
     */
    public function emailConfirm(Request $request, $confirmCode)
    {
        //檢查驗證碼
        $user = User::where('confirm_code', $confirmCode)->whereNull('confirm_at')->first();
        if (!$user) {
            return redirect()->route('index')->with('warning', '驗證連結無效。');
        }
        //更新資料
        $user->confirm_code = null;
        $user->confirm_at = Carbon::now()->toDateTimeString();
        $user->save();

        //自動綁定學生
        if (ends_with($user->email, '@fcu.edu.tw')) {
            $nid = preg_split('/@/', $user->email)[0];
            if (preg_match('/\\w\\d+/', $nid)) {
                $stuInfo = $this->fcuApiService->getStuInfo($nid);
                if ($stuInfo) {
                    $user->student()->save(Student::updateOrCreate(['nid' => $nid], [
                        'nid'       => $stuInfo['stu_id'],
                        'name'      => $stuInfo['stu_name'],
                        'class'     => $stuInfo['stu_class'],
                        'unit_name' => $stuInfo['unit_name'],
                        'dept_name' => $stuInfo['dept_name'],
                        'in_year'   => $stuInfo['in_year'],
                        'sex'       => $stuInfo['stu_sex'],
                    ]));
                    $user->update([
                        'name' => $user->student->name,
                    ]);

                    //Log
                    $this->logService->info("[Student][Bind] {$user->name} 綁定了 {$user->student->displayName}", [
                        'ip'      => request()->ip(),
                        'user'    => $user,
                        'student' => $user->student,
                    ]);
                }
            }
        }

        return redirect()->route('index')->with('global', '信箱驗證完成。');
    }

    /**
     * 重送驗證信頁面
     *
     * @return \Illuminate\View\View
     */
    public function resendConfirmMailPage()
    {
        $user = auth()->user();
        if ($user->isConfirmed) {
            return redirect()->route('index');
        }

        return view('auth.resend-confirm-mail', compact('user'));
    }

    /**
     * 重送驗證信
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendConfirmMail(Request $request)
    {
        //檢查冷卻時間（每次須等待5分鐘）
        $throttler = Throttle::get($request, 1, 5);
        if (!$throttler->attempt()) {
            return redirect()->route('auth.resend-confirm-mail')->with('warning', '信件請求過於頻繁，請等待5分鐘。');
        }
        $user = auth()->user();
        $this->generateConfirmCodeAndSendConfirmMail($user);

        return redirect()->route('index')->with('global', '驗證信件已重新發送。');
    }

    /**
     * 產生驗證代碼並發送驗證信件
     *
     * @param User $user
     */
    public function generateConfirmCodeAndSendConfirmMail(User $user)
    {
        //產生驗證碼
        $confirmCode = str_random(60);
        //產生驗證連結
        $link = route('auth.confirm', $confirmCode);
        //發送驗證郵件
        $this->mailService->sendEmailConfirmation($user, $link);
        //記錄驗證碼
        $user->confirm_code = $confirmCode;
        $user->save();
    }
}
