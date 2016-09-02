<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Point;
use App\Services\CheckInService;
use App\Services\FileService;
use App\Services\LogService;
use App\Student;
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
     * @var LogService
     */
    private $logService;
    /**
     * @var FileService
     */
    private $fileService;

    /**
     * PointController constructor.
     * @param CheckInService $checkInService
     * @param LogService $logService
     * @param FileService $fileService
     */
    public function __construct(CheckInService $checkInService, LogService $logService, FileService $fileService)
    {
        $this->checkInService = $checkInService;
        $this->logService = $logService;
        $this->fileService = $fileService;
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
            'student_nid' => 'required|exists:students,nid',
            'booth_id'    => 'required|exists:booths,id',
        ]);

        $student = Student::find($request->get('student_nid'));
        $booth = Booth::find($request->get('booth_id'));
        $this->checkInService->checkIn($booth, $student, false);

        //Log
        $operator = auth()->user();
        $this->logService->info(
            "[Point][Create] {$operator->name} 新增了 {$student->displayName} 在 {$booth->name} 的打卡記錄",
            [
                'ip'       => request()->ip(),
                'operator' => $operator,
                'student'  => $student,
                'booth'    => $booth,
            ]
        );

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
        //Log
        $student = $point->student;
        $booth = $point->booth;
        $operator = auth()->user();
        $this->logService->info(
            "[Point][Delete] {$operator->name} 移除了 {$student->displayName} 在 {$booth->name} 的打卡記錄",
            [
                'ip'       => request()->ip(),
                'operator' => $operator,
                'student'  => $student,
                'booth'    => $booth,
            ]
        );

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
        $dataTables = Datatables::of(Point::with('student', 'booth.type'))
            ->filterColumn('student_nid', function ($query, $keyword) {
                //FIXME: 過濾查詢優化
                $query->whereIn('student_nid', function ($query) use ($keyword) {
                    $query->select('students.nid')
                        ->from('students')
                        ->join('points', 'students.nid', '=', 'points.student_nid')
                        ->whereRaw('students.name LIKE ?', ['%' . $keyword . '%'])
                        ->orWhereRaw('students.nid LIKE ?', ['%' . strtoupper($keyword) . '%']);
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

    public function downloadXlsxFile()
    {
        $fileName = '打卡紀錄';
        $excelFile = $this->fileService->generateXlsxFile($fileName);
        $excelFile->download('xlsx');
    }
}
