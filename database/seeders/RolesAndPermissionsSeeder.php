<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $guardName = config('auth.defaults.guard');

        $permissionsByRole = [
            'admin' => [

                // roles
                "roles.rolesPermissions",
                "roles.index",
                "roles.create",
                "roles.store",
                "roles.update",
                "roles.edit",
                "roles.destroy",
                "roles.show",

                // users
                "users.index",
                "users.create",
                "users.store",
                "users.update",
                "users.edit",
                "users.destroy",
                "users.show",

                // reports
                "reports.index",
                "reports.view-report-central-report",
                "reports.view-report-complaint-percentage-report",
                "reports.view-report-complaint-saved-reasons-report",
                "reports.view-report-compare-request-type-between-years",
                "reports.view-report-complaints-inquiries-summary-by-source",
                "reports.view-report-offices-complaints-and-inquiries-summary-report",
                "reports.view-report-offices-saved-complaints-count-report",
                "reports.view-report-annual-sources-comparison",

                // complaints
                "complaints.index",
                "complaints.create",
                "complaints.store",
                "complaints.update",
                "complaints.edit",
                "complaints.reply",
                "complaints.destroy",
                "complaints.show",

                // responses ✅
                "responses.index",
                "responses.create",
                "responses.store",
                "responses.update",
                "responses.edit",
                "responses.reply",
                "responses.destroy",
                "responses.show",
                "responses.data",
            ],
        ];

        foreach ($permissionsByRole as $roleName => $permissions) {

            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => $guardName,
            ]);

            $permissionIds = [];

            foreach ($permissions as $name) {
                $permission = Permission::firstOrCreate(
                    [
                        'name' => $name,
                        'guard_name' => $guardName,
                    ],
                    [
                        'group' => ucfirst(explode('.', str_replace('_', ' ', $name))[0]),
                    ]
                );

                $permissionIds[] = $permission->id;
            }

            $role->syncPermissions($permissionIds);

            $user = User::where('userID', 'Nadine.Essam')->first();
            if ($user) {
                $user->assignRole($role);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}