<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'  => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
        ]);

        $role = Role::firstOrCreate(['name' => 'admin']);
        $user->assignRole($role);
    }
}
