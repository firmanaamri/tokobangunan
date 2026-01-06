<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@tokobangunan.com'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // Staff User
        User::firstOrCreate(
            ['email' => 'staff@tokobangunan.com'],
            [
                'name' => 'Staff Toko',
                'role' => 'staff',
                'password' => Hash::make('staff123'),
            ]
        );

        $this->command->info('Default users created:');
        $this->command->info('Admin: admin@tokobangunan.com / admin123');
        $this->command->info('Staff: staff@tokobangunan.com / staff123');
    }
}
