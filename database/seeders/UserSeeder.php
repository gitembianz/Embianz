<?php

namespace Database\Seeders;

use App\Models\User;
use App\Mail\NewUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $password = 'lolzalolza';
        User::truncate();
        $user = User::create([
            'name' => 'Dev Embianz',
            'email' => 'dev@embianz.com',
            'usertype' => 1,
            'password' => bcrypt($password),
        ]);
          try {
            Mail::to($user->email)->send(new NewUser($user->name, $user->email, $password));
            $this->command->info('User created and email sent successfully.');
        } catch (\Exception $e) {
            $this->command->warn('User created, but email could not be sent. Error: ' . $e->getMessage());
        }
    }
}
