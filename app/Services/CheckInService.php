<?php

namespace App\Services;

use App\Booth;
use App\Point;
use App\Student;
use Carbon\Carbon;

class CheckInService
{
    /**
     * @var \App\Services\RecordService
     */
    private $recordService;

    /**
     * CheckInService constructor.
     * @param RecordService $recordService
     */
    public function __construct(RecordService $recordService)
    {
        $this->recordService = $recordService;
    }

    /**
     * 打卡集點
     *
     * @param Booth $booth
     * @param Student $student
     * @param bool $hasTime
     * @return bool 打卡成功
     */
    public function checkIn(Booth $booth, Student $student, $hasTime = true)
    {
        //打卡集點
        Point::create([
            'student_nid' => $student->nid,
            'booth_id'    => $booth->id,
            'check_at'    => ($hasTime) ? Carbon::now() : null,
            'ip'          => \Request::getClientIp(),
        ]);
        //檢查是否達成目標
        $this->checkTarget($student);

        return true;
    }

    /**
     * 檢查是否達成目標
     *
     * @param Student $student
     */
    public function checkTarget(Student $student)
    {
        //檢查學生擁有抽獎資格
        if (!$student->isQualified) {
            return;
        }
        //檢查學生未擁有抽獎券
        if ($student->ticket) {
            return;
        }
        //取得完成任務進度
        $progress = $this->recordService->getStudentProgress($student);

        //逐一檢查是否完成
        foreach ($progress as $checkProgress) {
            if ($checkProgress['now'] < $checkProgress['target']) {
                //任何一項沒完成
                return;
            }
        }

        //建立抽獎劵
        $student->ticket()->create([]);
    }
}
