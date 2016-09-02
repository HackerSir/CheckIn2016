<?php

namespace App\Http\Controllers;

use App\Services\FcuApiService;
use App\Services\LogService;
use App\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * @var LogService
     */
    private $logService;
    /**
     * @var FcuApiService
     */
    private $fcuApiService;

    /**
     * StudentController constructor.
     * @param LogService $logService
     * @param FcuApiService $fcuApiService
     */
    public function __construct(LogService $logService, FcuApiService $fcuApiService)
    {
        $this->logService = $logService;
        $this->fcuApiService = $fcuApiService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::paginate();

        return view('student.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('student.create');
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
            'nid' => [
                'required',
                'unique:students,nid',
                'regex:/\\w\\d+/',
            ],
        ]);

        $stuInfo = $this->fcuApiService->getStuInfo($request->get('nid'));
        if (!$stuInfo) {
            return back()->with('warning', '查無此人');
        }

        $student = Student::create([
            'nid'       => $stuInfo['stu_id'],
            'name'      => $stuInfo['stu_name'],
            'class'     => $stuInfo['stu_class'],
            'unit_name' => $stuInfo['unit_name'],
            'dept_name' => $stuInfo['dept_name'],
            'in_year'   => $stuInfo['in_year'],
            'sex'       => $stuInfo['stu_sex'],
        ]);

        //Log
        $operator = auth()->user();
        $this->logService->info("[Student][Create] {$operator->name} 新增了 {$student->displayName}", [
            'ip'       => request()->ip(),
            'operator' => $operator,
            'student'  => $student,
        ]);

        return redirect()->route('student.index')->with('global', '學生已新增');
    }

    public function fetch(Request $request, Student $student)
    {
        $stuInfo = $this->fcuApiService->getStuInfo($student->nid);
        if (!$stuInfo) {
            return back()->with('warning', '無法更新資料');
        }

        $student->update([
            'name'      => $stuInfo['stu_name'],
            'class'     => $stuInfo['stu_class'],
            'unit_name' => $stuInfo['unit_name'],
            'dept_name' => $stuInfo['dept_name'],
            'in_year'   => $stuInfo['in_year'],
            'sex'       => $stuInfo['stu_sex'],
        ]);

        return redirect()->route('student.index')->with('global', '學生已更新');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //Log
        $operator = auth()->user();
        $this->logService->info("[Student][Delete] {$operator->name} 移除了 {$student->displayName}", [
            'ip'       => request()->ip(),
            'operator' => $operator,
            'student'  => $student,
        ]);

        $student->delete();

        return redirect()->route('student.index')->with('global', '學生已刪除');
    }
}
