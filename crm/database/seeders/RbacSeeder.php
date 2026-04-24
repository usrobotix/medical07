<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'coordinator']);
        Role::firstOrCreate(['name' => 'intake']);

        $user = User::firstOrCreate(
            ['email' => 'admin@medical07.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('ChangeMe123!'),
            ]
        );

        $user->syncRoles(['admin']);
    }
}