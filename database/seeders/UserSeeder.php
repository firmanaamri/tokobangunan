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
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@tokobangunan.com',
                'role' => 'admin',
                'password' => Hash::make('admin12345'),
            ]
        );

        // Staff User
        User::firstOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Staff Toko',
                'email' => 'staff@tokobangunan.com',
                'role' => 'staff',
                'password' => Hash::make('staff12345'),
            ]
        );

        $this->command->info('Default users created:');
        $this->command->info('Admin: admin / admin12345');
        $this->command->info('Staff: staff / staff12345');
    }
}
