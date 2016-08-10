<?php

namespace App\Services;

use App\Booth;
use App\Point;
use App\User;
use Carbon\Carbon;

class CheckInService
{
    /**
     * 打卡集點
     *
     * @param Booth $booth
     * @param User $user
     * @return bool 打卡成功
     */
    public function checkIn(Booth $booth, User $user)
    {
        //打卡集點
        Point::create([
            'user_id'  => $user->id,
            'booth_id' => $booth->id,
            'check_at' => Carbon::now(),
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
        //TODO:檢查使用者未擁有抽獎券
        //TODO:檢查完成任務
        //TODO:建立抽獎劵
    }
}
