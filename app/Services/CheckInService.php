<?php

namespace App\Services;

use App\Booth;
use App\User;

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
        //TODO:打卡集點
        return true;
    }

    /**
     * 檢查是否需要建立抽獎券
     *
     * @param User $user
     */
    public function checkTicket(User $user)
    {
        //TODO:檢查使用者未擁有抽獎券
        //TODO:檢查完成任務
        //TODO:建立抽獎劵
    }
}
