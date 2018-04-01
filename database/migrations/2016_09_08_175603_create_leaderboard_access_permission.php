<?php

use App\Role;
use App\Permission;
use Illuminate\Database\Migrations\Migration;

class CreateLeaderboardAccessPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permLeaderBoardAccess = Permission::create([
            'name'         => 'leaderBoard.access',
            'display_name' => '進入排行榜',
            'description'  => '進入排行榜檢視攤位排名',
        ]);

        // Find Admin and give permission to him
        /* @var Role $admin */
        $admin = Role::where('name', 'Admin')->first();
        $admin->attachPermission($permLeaderBoardAccess);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'leaderBoard.access')->delete();
    }
}
