<?php

namespace App\Services;

use App\Setting;
use App\Type;
use DB;

class RecordService
{
    /**
     * 打卡集點記錄（依據攤位聚合）
     * @param $nid
     * @return array|static[]
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
     * 計算「全部」紀錄
     * @param $nid
     * @param $typeIds
     * @return array|static[]
     */
    private function getCountedRecords($nid, $typeIds)
    {
        $countedRecords = DB::table('points')
            ->where('student_nid', $nid)
            ->select('booth_id', 'type_id', DB::raw('count(*) as count'))
            ->join('booths', 'points.booth_id', '=', 'booths.id')
            ->groupBy('booth_id', 'type_id')
            ->where(function ($query) use ($typeIds) {
                /* @var \Illuminate\Database\Query\Builder $query */
                $query->whereIn('type_id', $typeIds)
                    ->orWhereNull('type_id');
            })
            ->get();

        return $countedRecords;
    }

    /**
     * 取得總進度
     * @param $countedRecords
     * @return array
     */
    private function getTotalProgress($countedRecords)
    {
        $progress = [];
        $progress['total'] = [
            'now'    => count($countedRecords),
            'target' => Setting::get('GlobalTarget'),
        ];

        return $progress;
    }

    /**
     * 取得學生個人進度
     * @param $nid
     * @return array|null
     */
    public function getStudentProgress($nid)
    {
        if (!$nid) {
            return null;
        }

        $countedTypeIds = Type::where('counted', true)->pluck('id');
        $checkRecords = $this->getCheckRecords($nid);
        $countedRecords = $this->getCountedRecords($nid, $countedTypeIds);
        $progress = $this->getTotalProgress($countedRecords);

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