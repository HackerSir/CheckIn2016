<?php

namespace App\Http\Controllers;

use DB;
use App\Booth;
use App\Point;
use App\Student;

class LeaderBoardController extends Controller
{
    public function index()
    {
        //計算打卡數量（同一學生重複打卡不該重複計算）
        $pointCounts = Point::whereIn('id', function ($query) {
            /* @var \Illuminate\Database\Query\Builder $query */
            //有效打卡紀錄
            $query->select('id')
                ->from(with(new Point)->getTable())
                ->whereIn('student_nid', function ($query) {
                    /* @var \Illuminate\Database\Query\Builder $query */
                    //有投票資格的學生
                    $query->select('nid')
                        ->from(with(new Student)->getTable())
                        ->where('in_year', '=', '105')
                        ->orWhere('class', 'like', '%一年級%');
                });
        })
            ->select(DB::raw('booth_id,count(distinct(student_nid)) as total'))
            ->groupBy('booth_id')->get();
        //重新建構打卡數量資料
        $boothPointCount = [];
        foreach ($pointCounts as $pointCount) {
            $boothPointCount[$pointCount['booth_id']] = $pointCount['total'];
        }
        $booths = Booth::with('type', 'points')->get();
        //確保全部攤位都有數量資料
        foreach ($booths as $booth) {
            if (!isset($boothPointCount[$booth->id])) {
                $boothPointCount[$booth->id] = 0;
            }
        }
        //排序
        $booths = $booths->sortBy(function ($booth) use ($boothPointCount) {
            //根據打卡數量排序
            return $boothPointCount[$booth->id];
        }, SORT_REGULAR, true);

        return view('leaderBoard.index', compact('booths', 'boothPointCount'));
    }
}
