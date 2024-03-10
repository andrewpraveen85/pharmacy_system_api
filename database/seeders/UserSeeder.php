<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@example.com',
            'username' => 'owner',
            'role' => '1',
            'password' => Hash::make('password'),
            'status' => '1',
        ]);
        
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@example.com',
            'username' => 'manager',
            'role' => '2',
            'password' => Hash::make('password'),
            'status' => '1',
        ]);
        
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@example.com',
            'username' => 'cahier',
            'role' => '3',
            'password' => Hash::make('password'),
            'status' => '1',
        ]);
    }
}
