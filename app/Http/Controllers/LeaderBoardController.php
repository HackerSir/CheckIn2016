<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Point;
use App\Student;
use DB;

class LeaderBoardController extends Controller
{
    public function index()
    {
        //有投票資格的學生
        $validStudentNids = Student::where('students.in_year', '=', '105')
            ->orWhere('students.class', 'like', '%一年級%')
            ->pluck('nid')->toArray();
        //有效打卡紀錄
        $validPointIds = Point::whereIn('student_nid', $validStudentNids)->pluck('id')->toArray();
        //計算打卡數量（同一學生重複打卡不該重複計算）
        $pointCounts = Point::whereIn('id', $validPointIds)
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
