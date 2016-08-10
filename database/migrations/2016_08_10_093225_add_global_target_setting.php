<?php

use App\Setting;
use Illuminate\Database\Migrations\Migration;

class AddGlobalTargetSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::create([
            'name' => 'GlobalTarget',
            'type' => 'int',
            'desc' => '總目標攤位數量（到多少攤位打卡集點才能抽獎）'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('name', 'GlobalTarget')->delete();
    }
}
