<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketManagePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $permTicketManage = Permission::create([
            'name'         => 'ticket.manage',
            'display_name' => '管理抽獎券',
            'description'  => '查詢與管理抽獎券',
        ]);

        // Find Admin and give permission to him
        /* @var Role $admin */
        $admin = Role::where('name', 'Admin')->first();
        $admin->attachPermission($permTicketManage);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'ticket.manage')->delete();
    }
}
