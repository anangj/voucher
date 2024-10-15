<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = collect([
            ['name' => 'user create', 'module_name' => 'user',],
            ['name' => 'user update', 'module_name' => 'user',],
            ['name' => 'user delete', 'module_name' => 'user',],
            ['name' => 'user show', 'module_name' => 'user',],
            ['name' => 'user index', 'module_name' => 'user',],

            ['name' => 'permission index', 'module_name' => 'permission'],
            ['name' => 'permission create', 'module_name' => 'permission'],
            ['name' => 'permission update', 'module_name' => 'permission'],
            ['name' => 'permission delete', 'module_name' => 'permission'],
            ['name' => 'permission show', 'module_name' => 'permission'],

            ['name' => 'role index', 'module_name' => 'role'],
            ['name' => 'role create', 'module_name' => 'role'],
            ['name' => 'role update', 'module_name' => 'role'],
            ['name' => 'role delete', 'module_name' => 'role'],
            ['name' => 'role show', 'module_name' => 'role'],

            ['name' => 'package_voucher index', 'module_name' => 'package_voucher'],
            ['name' => 'package_voucher create', 'module_name' => 'package_voucher'],
            ['name' => 'package_voucher update', 'module_name' => 'package_voucher'],
            ['name' => 'package_voucher delete', 'module_name' => 'package_voucher'],
            ['name' => 'package_voucher show', 'module_name' => 'package_voucher'],

            ['name' => 'voucher index', 'module_name' => 'voucher'],
            ['name' => 'voucher create', 'module_name' => 'voucher'],
            ['name' => 'voucher update', 'module_name' => 'voucher'],
            ['name' => 'voucher delete', 'module_name' => 'voucher'],
            ['name' => 'voucher show', 'module_name' => 'voucher'],

            ['name' => 'patient index', 'module_name' => 'patient'],
            ['name' => 'patient create', 'module_name' => 'patient'],
            ['name' => 'patient update', 'module_name' => 'patient'],
            ['name' => 'patient delete', 'module_name' => 'patient'],
            ['name' => 'patient show', 'module_name' => 'patient'],

            ['name' => 'database_backup viewAny', 'module_name' => 'database_backup'],
            ['name' => 'database_backup create', 'module_name' => 'database_backup'],
            ['name' => 'database_backup delete', 'module_name' => 'database_backup'],
            ['name' => 'database_backup download', 'module_name' => 'database_backup'],

            ['name'=>'menu users_list', 'module_name'=>'menu'],
            ['name'=>'menu role_permission', 'module_name'=>'menu'],
            ['name'=>'menu role_permission_permissions', 'module_name'=>'menu'],
            ['name'=>'menu role_permission_roles', 'module_name'=>'menu'],
            ['name'=>'menu setting', 'module_name'=>'menu'],
            ['name'=>'menu database_backup', 'module_name'=>'menu'],
            ['name'=>'menu package_voucher', 'module_name'=>'menu'],
            ['name'=>'menu voucher', 'module_name'=>'menu'],
            ['name'=>'menu patient', 'module_name'=>'menu'],

        ]);

        $web = collect([]);

        $permissions->map(function ($permission) use ($web) {
            $web->push([
                'name' => $permission['name'],
                'module_name' => $permission['module_name'],
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Permission::insert($web->toArray());
    }
}
