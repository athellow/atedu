<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin::create([
            'name' => 'Athellow',
            'email' => 'athellow@outlook.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(10),
        ]);
    }
}
