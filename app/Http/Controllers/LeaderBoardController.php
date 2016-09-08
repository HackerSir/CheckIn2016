<?php

namespace App\Http\Controllers;

use App\Booth;


class LeaderBoardController extends Controller
{
    public function index()
    {
        $booths = Booth::with('type', 'points')->get()
            ->sortBy(function ($booth) {
                return $booth->points->count();
            }, SORT_REGULAR, true);

        return view('leaderBoard.index', compact('booths'));
    }
}
