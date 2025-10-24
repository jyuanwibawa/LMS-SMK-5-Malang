<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($this->isRowEmpty($row)) {
                continue;
            }

            $email = strtolower(trim((string) ($row['email'] ?? '')));
            $name = trim((string) ($row['name'] ?? ''));
            if ($email === '' || $name === '') {
                continue;
            }

            $identity = trim((string) ($row['identity_number'] ?? ''));
            $gender = $this->normalizeGender((string) ($row['jenis_kelamin'] ?? ''));
            $roleName = strtolower(trim((string) ($row['role'] ?? '')));
            $password = (string) ($row['password'] ?? '');

            $roleId = $this->resolveRoleId($roleName);

            $finalPassword = $password !== '' ? $password : Str::password(10);

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'identity_number' => $identity !== '' ? $identity : null,
                    'jenis_kelamin' => $gender,
                    'role_id' => $roleId,
                    'password' => $finalPassword,
                ]
            );
        }
    }

    private function resolveRoleId(?string $roleName): ?int
    {
        if (!$roleName) {
            return null;
        }
        $map = [
            'admin' => 'admin',
            'guru' => 'guru',
            'siswa' => 'siswa',
        ];
        $key = strtolower($roleName);
        $target = $map[$key] ?? $key;
        $role = Role::where('name', $target)->first();
        return $role?->id;
    }

    private function normalizeGender(string $value): ?string
    {
        $v = strtolower(trim($value));
        if ($v === '') return null;
        if (in_array($v, ['l', 'laki', 'laki-laki', 'laki laki', 'male'])) {
            return 'Laki-Laki';
        }
        if (in_array($v, ['p', 'perempuan', 'female'])) {
            return 'Perempuan';
        }
        // If already in proper casing
        if (in_array($value, ['Laki-Laki', 'Perempuan'])) {
            return $value;
        }
        return null;
    }

    private function isRowEmpty($row): bool
    {
        if ($row instanceof Collection) {
            $row = $row->toArray();
        }
        foreach ($row as $val) {
            if ($val !== null && trim((string) $val) !== '') {
                return false;
            }
        }
        return true;
    }
}
