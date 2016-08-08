<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeAndBoothManagePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permTypeManage = Permission::create([
            'name'         => 'type.manage',
            'display_name' => '管理攤位類型',
            'description'  => '管理攤位類型資料'
        ]);
        $permBoothManage = Permission::create([
            'name'         => 'booth.manage',
            'display_name' => '管理攤位',
            'description'  => '管理攤位資料'
        ]);

        // Find Admin and give permission to him
        /* @var Role $admin */
        $admin = Role::where('name', 'Admin')->first();
        $admin->attachPermission($permTypeManage);
        $admin->attachPermission($permBoothManage);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'type.manage')->delete();
        Permission::where('name', 'booth.manage')->delete();
    }
}
