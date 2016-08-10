<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Point;
use App\Services\CheckInService;
use App\User;
use Illuminate\Http\Request;

class PointController extends Controller
{
    /**
     * @var CheckInService
     */
    protected $checkInService;

    /**
     * PointController constructor.
     * @param CheckInService $checkInService
     */
    public function __construct(CheckInService $checkInService)
    {
        $this->checkInService = $checkInService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $points = Point::with('user', 'booth')->orderBy('created_at', 'desc')->paginate();

        return view('point.index', compact('points'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('point.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id'  => 'required|exists:users,id',
            'booth_id' => 'required|exists:booths,id',
        ]);

        $user = User::find($request->get('user_id'));
        $booth = Booth::find($request->get('booth_id'));
        $this->checkInService->checkIn($booth, $user, false);

        return redirect()->route('point.index')->with('global', '打卡集點記錄已新增');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Point $point
     * @return \Illuminate\Http\Response
     */
    public function destroy(Point $point)
    {
        $point->delete();

        return redirect()->route('point.index')->with('global', '打卡集點記錄已刪除');
    }
}
