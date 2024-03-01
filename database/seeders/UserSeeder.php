<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordEmail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $password = 'lolzalolza';
        User::truncate();
        User::create([
            'name' => 'Dev Embianz',
            'email' => 'dev@embianz.com',
            'usertype' => 1,
            'password' => bcrypt($password),
        ]);
    }
}
