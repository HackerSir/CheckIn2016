<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Point;
use App\Services\CheckInService;
use App\Services\RecordService;
use App\Type;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    protected $checkInService;
    protected $recordService;

    /**
     * CheckController constructor.
     * @param CheckInService $checkInService
     * @param RecordService $recordService
     */
    public function __construct(CheckInService $checkInService, RecordService $recordService)
    {
        $this->checkInService = $checkInService;
        $this->recordService = $recordService;
    }

    /**
     * 進度＆抽獎券
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $user = auth()->user();
        $student = $user->student;
        if (!$student) {
            return redirect()->route('index')->with('warning', '無法取得學生資料');
        }

        //類型
        $types = Type::all();

        //取得打卡進度
        $progress = $this->recordService->getStudentProgress($student);

        //最近打卡集點記錄
        $lastPoints = Point::with('student', 'booth.type')->where('student_nid', $student->nid)
            ->orderBy('created_at', 'desc')->take(5)->get();

        return view('check.index', compact('types', 'progress', 'lastPoints'));
    }

    public function getRecord()
    {
        $user = auth()->user();
        $student = $user->student;
        if (!$student) {
            return redirect()->route('index')->with('warning', '無法取得學生資料');
        }
        $points = Point::with('student', 'booth.type')->where('student_nid', $student->nid)
            ->orderBy('created_at', 'desc')->groupBy('booth_id')->paginate(10);

        return view('check.record', compact('points'));
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
        if (!env('ALLOW_CHECKIN', false)) {
            return back()->with('warning', '現在不允許打卡！');
        }
        $user = auth()->user();
        $student = $user->student;
        if (!$student) {
            return redirect()->route('index')->with('warning', '無法取得學生資料');
        }
        //打卡集點
        $this->checkInService->checkIn($checkBooth, $student);

        return redirect()->route('check.index')->with('global', '打卡集點成功');
    }
}
