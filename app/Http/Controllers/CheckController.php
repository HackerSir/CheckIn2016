<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Point;
use App\Services\CheckInService;
use App\Setting;
use App\Type;
use DB;
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
        //打卡集點記錄（依據攤位聚合）
        $checkRecords = DB::table('points')
            ->where('user_id', $user->id)
            ->select('booth_id', 'type_id', DB::raw('count(*) as count'))
            ->join('booths', 'points.booth_id', '=', 'booths.id')
            ->groupBy('booth_id', 'type_id')
            ->get();
        //進度
        $progress = [];
        $progress['total'] = [
            'now'    => count($checkRecords),
            'target' => Setting::get('GlobalTarget'),
        ];
        foreach ($types as $type) {
            $nowCount = count(array_filter($checkRecords, function ($value) use ($type) {
                return $value->type_id == $type->id;
            }));
            $progress[$type->id] = [
                'now'    => $nowCount,
                'target' => $type->target,
            ];
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
