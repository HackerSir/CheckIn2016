<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class CreateSettingEditPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permSettingManage = Permission::create([
            'name'         => 'setting.manage',
            'display_name' => '管理網站設定',
            'description'  => '進入設定面板，檢視、修改網站設定',
        ]);

        /** @var Role $admin */
        $admin = Role::where('name', 'admin')->first();
        $admin->attachPermission($permSettingManage);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'setting.manage')->delete();
    }
}
