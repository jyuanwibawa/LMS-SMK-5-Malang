<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $guruRole = Role::where('name', 'guru')->first();

        User::updateOrCreate(
            [
                'email' => 'adminsmk5malang@gmail.com',
            ],
            [
                'name' => 'Admin SMK 5 Malang',
                'identity_number' => '123456',
                'jenis_kelamin' => 'Laki-Laki',
                'password' => Hash::make('adminsmk5malang'),
                'role_id' => $adminRole?->id,
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'munifguru@gmail.com',
            ],
            [
                'name' => 'Munif Hamdani',
                'identity_number' => '1234567890',
                'jenis_kelamin' => 'Laki-Laki',
                'password' => Hash::make('munifgurusmk5'),
                'role_id' => $guruRole?->id,
            ]
        );
    }
}
