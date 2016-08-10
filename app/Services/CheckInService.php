<?php

namespace App\Services;

use App\Booth;
use App\Point;
use App\Setting;
use App\Type;
use App\User;
use Carbon\Carbon;

class CheckInService
{
    /**
     * 打卡集點
     *
     * @param Booth $booth
     * @param User $user
     * @param bool $hasTime
     * @return bool 打卡成功
     */
    public function checkIn(Booth $booth, User $user, $hasTime = true)
    {
        //打卡集點
        Point::create([
            'user_id'  => $user->id,
            'booth_id' => $booth->id,
            'check_at' => ($hasTime) ? Carbon::now() : null,
        ]);
        //檢查是否達成目標
        $this->checkTarget($user);

        return true;
    }

    /**
     * 檢查是否達成目標
     *
     * @param User $user
     */
    public function checkTarget(User $user)
    {
        //檢查使用者未擁有抽獎券
        if (count($user->tickets)) {
            return;
        }
        //檢查完成任務
        //類型
        $types = Type::all();
        //FIXME: 查詢可優化
        //打卡集點記錄
        $points = Point::with('user', 'booth.type')->where('user_id', $user->id)->groupBy('booth_id')->get();
        //進度
        $progress = [];
        $progress['total'] = [
            'now'    => count($points),
            'target' => Setting::get('GlobalTarget'),
        ];
        foreach ($types as $type) {
            $progress[$type->id] = [
                'now'    => 0,
                'target' => $type->target,
            ];
        }

        foreach ($points as $point) {
            if (isset($progress[$point->booth->type_id])) {
                $progress[$point->booth->type_id]['now']++;
            }
        }

        //逐一檢查是否完成
        foreach ($progress as $checkProgress) {
            if ($checkProgress['now'] < $checkProgress['target']) {
                //任何一項沒完成
                return;
            }
        }

        //建立抽獎劵
        $user->tickets()->create([]);
    }
}
