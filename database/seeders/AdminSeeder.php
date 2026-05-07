<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'System Administrator',
                'password' => Hash::make('admin1234'),
                'role'     => 'admin',
            ]
        );

        $this->command->info('Admin created: admin@gmail.com / admin1234');
    }
}