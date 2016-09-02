<?php

namespace App\Services;

use App\Booth;
use App\Point;
use App\Setting;
use App\Student;
use App\Type;
use Carbon\Carbon;
use DB;

class CheckInService
{
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
        //檢查學生未擁有抽獎券
        if ($student->ticket) {
            return;
        }
        //檢查完成任務
        //FIXME: 程式碼重複（CheckController）
        //類型
        $types = Type::all();
        //打卡集點記錄（依據攤位聚合）
        $checkRecords = DB::table('points')
            ->where('student_nid', $student->nid)
            ->select('booth_id', 'type_id', DB::raw('count(*) as count'))
            ->join('booths', 'points.booth_id', '=', 'booths.id')
            ->groupBy('booth_id', 'type_id')
            ->get();
        //計算「全部」
        $countedTypeIds = Type::where('counted', true)->pluck('id');
        $countedRecords = DB::table('points')
            ->where('student_nid', $student->nid)
            ->select('booth_id', 'type_id', DB::raw('count(*) as count'))
            ->join('booths', 'points.booth_id', '=', 'booths.id')
            ->groupBy('booth_id', 'type_id')
            ->where(function ($query) use ($countedTypeIds) {
                /* @var \Illuminate\Database\Query\Builder $query */
                $query->whereIn('type_id', $countedTypeIds)
                    ->orWhereNull('type_id');
            })
            ->get();
        //進度
        $progress = [];
        $progress['total'] = [
            'now'    => count($countedRecords),
            'target' => Setting::get('GlobalTarget'),
        ];
        foreach ($types as $type) {
            $nowCount = count(array_filter($checkRecords, function ($value) use ($type) {
                return $value->type_id == $type->id;
            }));
            $progress[$type->id] = [
                'now'    => $nowCount,
                'target' => $type->target,
            ];
        }

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
