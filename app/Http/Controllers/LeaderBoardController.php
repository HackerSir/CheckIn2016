<?php

namespace App\Http\Controllers;

use App\Booth;

class LeaderBoardController extends Controller
{
    public function index()
    {
        //FIXME: 同一學生重複打卡不該重複計算
        //TODO: 只計算有投票資格的打卡
        $booths = Booth::with('type', 'points')->get()
            ->sortBy(function ($booth) {
                return $booth->points->count();
            }, SORT_REGULAR, true);

        return view('leaderBoard.index', compact('booths'));
    }
}
