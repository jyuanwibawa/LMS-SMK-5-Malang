<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DemoDataSeeder::class,
        ]);

        $adminRole = Role::where('name', 'admin')->first();       
        $guruRole = Role::where('name', 'guru')->first();
        $siswaRole = Role::where('name', 'siswa')->first();       

        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@smk5malang.sch.id'],
            [
                'name' => 'Admin',
                'identity_number' => '0000000001',
                'jenis_kelamin' => 'Laki-Laki',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole?->id,
            ]
        );

        // Guru User
        User::updateOrCreate(
            ['email' => 'munifguru@gmail.com'],
            [
                'name' => 'Munif Hamdani',
                'identity_number' => '1234567890',
                'jenis_kelamin' => 'Laki-Laki',
                'password' => Hash::make('munifgurusmk5'),        
                'role_id' => $guruRole?->id,
            ]
        );

        // Siswa User
        User::updateOrCreate(
            ['email' => 'siswa1@smk5malang.sch.id'],
            [
                'name' => 'Budi Santoso',
                'identity_number' => '1234567891',
                'jenis_kelamin' => 'Laki-Laki',
                'password' => Hash::make('siswa123'),
                'role_id' => $siswaRole?->id,
            ]
        );

        // Tambahkan lebih banyak siswa jika diperlukan
        $siswaData = [
            [
                'name' => 'Ani Lestari',
                'email' => 'aniles@smk5malang.sch.id',
                'identity_number' => '1234567892',
                'jenis_kelamin' => 'Perempuan'
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'citrad@smk5malang.sch.id',
                'identity_number' => '1234567893',
                'jenis_kelamin' => 'Perempuan'
            ]
        ];

        foreach ($siswaData as $siswa) {
            User::updateOrCreate(
                ['email' => $siswa['email']],
                array_merge($siswa, [
                    'password' => Hash::make('siswa123'),
                    'role_id' => $siswaRole?->id,
                ])
            );
        }
    }
}