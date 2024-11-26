<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listPermissions = [
            'create-roles',
            'read-roles',
            'update-roles',
            'delete-roles',
            'assign-permissions',
            'create-permissions',
            'read-permissions',
            'update-permissions',
            'delete-permissions',
        ];

        foreach ($listPermissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // list custom permission
        $bahanPermission = ["create-bahan", "read-bahan", "update-bahan", 'delete-bahan'];
        foreach ($bahanPermission as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        $satuanPermission = ["create-satuan", "read-satuan", "update-satuan", 'delete-satuan'];
        foreach ($satuanPermission as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        $tukangPermission = ["create-tukang", "read-tukang", "update-tukang", 'delete-tukang'];
        foreach ($tukangPermission as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        $tukangPermission = ["create-vendor", "read-vendor", "update-vendor", 'delete-vendor'];
        foreach ($tukangPermission as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }
    }
}
