<?php

namespace Database\Seeders;

use App\Application\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => 'karin',
            "email" => env('ADMIN_USER_EMAIL'),
            "password" => env('ADMIN_PASSWORD'),
            "type" => "admin"
        ]);
    }
}
