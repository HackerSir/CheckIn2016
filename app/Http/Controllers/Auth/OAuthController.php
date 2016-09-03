<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\FcuApiService;

class OAuthController extends Controller
{
    /**
     * @var FcuApiService
     */
    private $fcuApiService;

    /**
     * OAuthController constructor.
     * @param FcuApiService $fcuApiService
     */
    public function __construct(FcuApiService $fcuApiService)
    {
        $this->fcuApiService = $fcuApiService;
    }

    public function index()
    {
        //檢查設定
        $OAuthUrl = env('FCU_API_URL_OAUTH');
        $clientId = env('FCU_API_CLIENT_ID');
        $clientUrl = env('FCU_API_CLIENT_URL');
        if (!$OAuthUrl || !$clientId || !$clientUrl) {
            return back()->with('warning', '目前未開放');
        }
        $data = [
            'client_id'  => $clientId,
            'client_url' => $clientUrl,
        ];
        //重導向到OAuth登入頁面
        $redirectUrl = $OAuthUrl . '?' . http_build_query($data);

        return redirect($redirectUrl);
    }

    public function login()
    {
        $userCode = \Request::get('user_code');
        if (!$userCode) {
            return redirect()->route('index')->with('warning', '登入失敗');
        }

        //利用User Code取得學號
        $userInfo = $this->fcuApiService->getLoginUser($userCode);
        //TODO: 嘗試找出使用者
        dd(\Request::instance(), \Request::all(), $userInfo);
    }
}
