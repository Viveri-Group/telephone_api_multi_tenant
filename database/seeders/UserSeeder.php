<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /* todo - remove*/
        $user = User::factory(['name'=>'Dean','email'=>'dean.haines@viverigroup.com', 'password' => bcrypt('Password123')])->create();

        $token = $user->createToken('api')->plainTextToken;

        echo "\n\nToken for user {$user->email}: $token\n\n";
    }
}
