<?php

namespace App\Services;

use DB;
use App\Type;
use App\Setting;
use App\Student;

class RecordService
{
    /**
     * 打卡集點記錄（依據攤位聚合）（個人）
     *
     * @param string $nid
     * @return array
     */
    private function getCheckRecords($nid)
    {
        $checkRecords = DB::table('points')
            ->where('student_nid', $nid)
            ->select('booth_id', 'type_id', DB::raw('count(*) as count'))
            ->join('booths', 'points.booth_id', '=', 'booths.id')
            ->groupBy('booth_id', 'type_id')
            ->get();

        return $checkRecords;
    }

    /**
     * 列入「全部」的紀錄總數（個人）
     *
     * @param string $nid
     * @return int
     */
    private function getCountedRecordCount($nid)
    {
        /* @var array $countedTypeIds 列入「全部」的類型ID */
        $countedTypeIds = Type::where('counted', true)->pluck('id');

        $countedRecords = DB::table('points')
            ->where('student_nid', $nid)
            ->select('booth_id', 'type_id', DB::raw('count(*) as count'))
            ->join('booths', 'points.booth_id', '=', 'booths.id')
            ->groupBy('booth_id', 'type_id')
            ->where(function ($query) use ($countedTypeIds) {
                /* @var \Illuminate\Database\Query\Builder $query */
                $query->whereIn('type_id', $countedTypeIds)
                    ->orWhereNull('type_id');
            })
            ->get();

        return count($countedRecords);
    }

    /**
     * 總進度（個人）
     *
     * @param string $nid
     * @return array
     */
    private function getTotalProgress($nid)
    {
        /* @var int $countedRecordCount 列入「全部」的紀錄總數（個人） */
        $countedRecordCount = $this->getCountedRecordCount($nid);

        $totalProgress = [
            'now'    => $countedRecordCount,
            'target' => Setting::get('GlobalTarget'),
        ];

        return $totalProgress;
    }

    /**
     * 整體進度（個人）
     *
     * @param Student $student
     * @return array
     */
    public function getStudentProgress(Student $student)
    {
        if (!$student) {
            return null;
        }
        $nid = $student->nid;

        /* @var array $checkRecords 打卡集點記錄（依據攤位聚合）（個人） */
        $checkRecords = $this->getCheckRecords($nid);
        /* @var array $progress 總進度（個人） */
        $progress = [];
        $progress['total'] = $this->getTotalProgress($nid);

        $types = Type::all();
        foreach ($types as $type) {
            $nowCount = count(array_filter($checkRecords, function ($value) use ($type) {
                return $value->type_id == $type->id;
            }));
            $progress[$type->id] = [
                'now'    => $nowCount,
                'target' => $type->target,
            ];
        }

        return $progress;
    }
}
