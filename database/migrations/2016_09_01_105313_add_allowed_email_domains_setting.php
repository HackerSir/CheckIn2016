<?php

use App\Setting;
use Illuminate\Database\Migrations\Migration;

class AddAllowedEmailDomainsSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::create([
            'name' => 'AllowedEmailDomains',
            'type' => 'multiline',
            'desc' => '允許註冊的信箱域名（每行一組，不含@）',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('name', 'AllowedEmailDomains')->delete();
    }
}
