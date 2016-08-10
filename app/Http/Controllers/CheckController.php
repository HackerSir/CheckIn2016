<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Point;
use App\Services\CheckInService;
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
        //最近打卡集點記錄
        $lastPoints = Point::with('user', 'booth.type')->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')->take(5)->get();

        return view('check.index', compact('lastPoints'));
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
