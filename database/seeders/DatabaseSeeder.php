<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //  $this->call(UserSeeder::class);
         $this->call(RolePermissionSeeder::class);
         $this->call(UserRolePermissionSeeder::class);
         $this->call(BasicinfoSeeder::class);
         $this->call(AdminSeeder::class);
         $this->call(AccounttypeSeeder::class);
         $this->call(AccountpackageSeeder::class);
         $this->call(NewsupdateSeeder::class);

    }
}
