<?php

namespace Database\Seeders\Auth;

use App\Events\Backend\UserCreated;
use App\Models\User;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();


        // Add the master administrator, user id of 1
        $users = [
            [
                'first_name'        => 'Super',
                'last_name'         => 'Admin',
                'name'              => 'Super Admin',
                'email'             => 'super@admin.com',
                'password'          => Hash::make('secret'),
                'username'          => '100001',
                'mobile'            => '123',
                'date_of_birth'     => "2020-01-20",
                'avatar'            => 'img/default-avatar.jpg',
                'gender'            => 'male',
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Admin',
                'last_name'         => 'Istrator',
                'name'              => 'Admin Istrator',
                'email'             => 'admin@admin.com',
                'password'          => Hash::make('secret'),
                'username'          => '100002',
                'mobile'            => '123',
                'date_of_birth'     => "2020-01-20",
                'avatar'            => 'img/default-avatar.jpg',
                'gender'            => 'male',
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'kepala',
                'last_name'         => 'User User',
                'name'              => 'kepala',
                'email'             => 'kepala@smkn1kokap.sch.id',
                'password'          => Hash::make('secret'),
                'username'          => '100003',
                'mobile'            => '123',
                'date_of_birth'     => "2020-01-20",
                'avatar'            => 'img/default-avatar.jpg',
                'gender'            => 'male',
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'General',
                'last_name'         => 'User',
                'name'              => 'General User',
                'email'             => 'user@user.com',
                'password'          => Hash::make('secret'),
                'username'          => '100005',
                'mobile'            => '123',
                'date_of_birth'     => "2020-01-20",
                'avatar'            => 'img/default-avatar.jpg',
                'gender'            => 'male',
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        foreach ($users as $user_data) {
            $user = User::create($user_data);

            event(new UserCreated($user));
        }

        Schema::enableForeignKeyConstraints();
    }
}
