<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Point;
use App\Services\CheckInService;
use App\Setting;
use App\Type;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    protected $checkInService;

    /**
     * CheckController constructor.
     * @param CheckInService $checkInService
     */
    public function __construct(CheckInService $checkInService)
    {
        $this->checkInService = $checkInService;
    }

    /**
     * 進度＆抽獎券
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $user = auth()->user();
        //類型
        $types = Type::all();
        //FIXME: 查詢可優化
        //打卡集點記錄
        $points = Point::with('user', 'booth.type')->where('user_id', $user->id)->groupBy('booth_id')->get();
        //進度
        $progress = [];
        $progress['total'] = [
            'now'    => count($points),
            'target' => Setting::get('GlobalTarget'),
        ];
        foreach ($types as $type) {
            $progress[$type->id] = [
                'now'    => 0,
                'target' => $type->target,
            ];
        }

        foreach ($points as $point) {
            if (isset($progress[$point->booth->type_id])) {
                $progress[$point->booth->type_id]['now']++;
            }
        }

        //最近打卡集點記錄
        $lastPoints = Point::with('user', 'booth.type')->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')->take(5)->get();

        return view('check.index', compact('types', 'progress', 'lastPoints'));
    }

    /**
     * 攤位頁面（有打卡集點按鈕）
     *
     * @param Booth $checkBooth
     * @return \Illuminate\Http\Response
     */
    public function getBooth(Booth $checkBooth)
    {
        return view('check.booth', ['booth' => $checkBooth]);
    }

    /**
     * 打卡集點
     *
     * @param Booth $checkBooth
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function postBooth(Booth $checkBooth, Request $request)
    {
        $user = auth()->user();
        //打卡集點
        $this->checkInService->checkIn($checkBooth, $user);

        return redirect()->route('check.index')->with('global', '打卡集點成功');
    }
}
