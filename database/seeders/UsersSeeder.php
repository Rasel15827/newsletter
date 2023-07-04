<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        //Admin
        $user = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@dstudio.asia',
            'password' => Hash::make('45625899'),
            'status' => 'active',
        ]);
        $user->roles()->attach(Role::where('slug', 'admin')->first());

        for ($i = 1; $i <= 10; $i++) {
            //Admin
            $user = User::create([
                'first_name' => 'User',
                'last_name' => $i,
                'email' => 'user'.$i.'@gmail.com',
                'password' => Hash::make('45625899'),
                'status' => 'active',
            ]);
            $user->roles()->attach(Role::where('slug', 'user')->first());
        }
    }
}
