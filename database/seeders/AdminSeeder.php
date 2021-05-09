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
            "email" => config('admin.email'),
            "password" => config('admin.password'),
            "type" => "admin",
            "phone" => "12121212"
        ]);
    }
}
