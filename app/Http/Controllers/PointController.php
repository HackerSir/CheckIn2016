<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Point;
use App\Services\CheckInService;
use App\User;
use Datatables;
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
        return view('point.index');
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

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $dataTables = Datatables::of(Point::with('user', 'booth'))
            ->filterColumn('user_id', function ($query, $keyword) {
                //FIXME: 過濾查詢優化
                $query->whereIn('user_id', function ($query) use ($keyword) {
                    $query->select('users.id')
                        ->from('users')
                        ->join('points', 'users.id', '=', 'points.user_id')
                        ->whereRaw('users.name LIKE ?', ['%' . $keyword . '%']);
                });
            })
            ->filterColumn('booth_id', function ($query, $keyword) {
                //FIXME: 過濾查詢優化
                //TODO: 連同攤位分類名稱一起查詢
                $query->whereIn('booth_id', function ($query) use ($keyword) {
                    $query->select('booths.id')
                        ->from('booths')
                        ->join('points', 'booths.id', '=', 'points.booth_id')
                        ->whereRaw('booths.name LIKE ?', ['%' . $keyword . '%']);
                });
            })
            ->make(true);

        return $dataTables;
    }
}
