<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ប្រើ updateOrCreate ជំនួស create
        // ន័យរបស់វាគឺ៖ រកមើល user ដែលមាន email នេះ...
        // - បើមានហើយ => Update password ថ្មីឱ្យ
        // - បើអត់ទាន់មាន => បង្កើតថ្មី

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // លក្ខខណ្ឌស្វែងរក
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'), // Password ដែលចង់បាន
            ]
        );
    }
}