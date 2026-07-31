<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $branchId = Str::uuid();
        
        DB::table('branches')->insert([
            'id' => $branchId,
            'name' => 'NS Enterprises - Main Branch',
            'address' => 'No 123, Main Street, Colombo',
            'phone' => '0771234567',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => Str::uuid(),
            'branch_id' => $branchId,
            'name' => 'System Admin',
            'email' => 'admin@ns.lk',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('shop_settings')->insert([
            'id' => Str::uuid(),
            'branch_id' => $branchId,
            'shop_name' => 'NS Enterprises',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
