<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
