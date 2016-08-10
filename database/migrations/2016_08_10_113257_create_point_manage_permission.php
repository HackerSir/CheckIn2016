<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointManagePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permPointManage = Permission::create([
            'name'         => 'point.manage',
            'display_name' => '管理打卡集點',
            'description'  => '管理、手動新增打卡集點記錄',
        ]);

        // Find Admin and give permission to him
        /* @var Role $admin */
        $admin = Role::where('name', 'Admin')->first();
        $admin->attachPermission($permPointManage);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'point.manage')->delete();
    }
}
