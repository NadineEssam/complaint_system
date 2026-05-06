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

        // 🔥 ترجمة الجروبات
        $groupTranslations = [
            'roles' => 'الأدوار',
            'users' => 'المستخدمين',
            'reports' => 'التقارير',
            'complaints' => 'الشكاوى',
            'responses' => 'الردود',
        ];

        $permissionsByRole = [
            'admin' => [

                "roles.rolesPermissions" => "صلاحيات الأدوار",
                "roles.index" => "عرض الأدوار",
                "roles.create" => "إضافة دور",
                "roles.store" => "حفظ الدور",
                "roles.update" => "تحديث الدور",
                "roles.edit" => "تعديل الدور",
                "roles.destroy" => "حذف الدور",
                "roles.show" => "عرض الدور",

                "users.index" => "عرض المستخدمين",
                "users.create" => "إضافة مستخدم",
                "users.store" => "حفظ المستخدم",
                "users.update" => "تحديث المستخدم",
                "users.edit" => "تعديل المستخدم",
                "users.destroy" => "حذف المستخدم",
                "users.show" => "عرض المستخدم",

                "reports.index" => "عرض التقارير",
                "reports.view-report-central-report" => "تقرير مركزي",
                "reports.view-report-complaint-percentage-report" => "نسبة الشكاوى",
                "reports.view-report-complaint-saved-reasons-report" => "أسباب حفظ الشكاوى",
                "reports.view-report-compare-request-type-between-years" => "مقارنة الطلبات بين السنوات",
                "reports.view-report-complaints-inquiries-summary-by-source" => "ملخص الشكاوى حسب المصدر",
                "reports.view-report-offices-complaints-and-inquiries-summary-report" => "ملخص المكاتب",
                "reports.view-report-offices-saved-complaints-count-report" => "عدد الشكاوى المحفوظة",
                "reports.view-report-annual-sources-comparison" => "مقارنة المصادر السنوية",

                "complaints.index" => "عرض الشكاوى",
                "complaints.create" => "إضافة شكوى",
                "complaints.store" => "حفظ الشكوى",
                "complaints.update" => "تحديث الشكوى",
                "complaints.edit" => "تعديل الشكوى",
                "complaints.reply" => "الرد على الشكوى",
                "complaints.destroy" => "حذف الشكوى",
                "complaints.show" => "عرض الشكوى",

                "responses.index" => "عرض الردود",
                "responses.create" => "إضافة رد",
                "responses.store" => "حفظ الرد",
                "responses.update" => "تحديث الرد",
                "responses.edit" => "تعديل الرد",
                "responses.reply" => "الرد",
                "responses.destroy" => "حذف الرد",
                "responses.show" => "عرض الرد",
                "responses.data" => "بيانات الردود",
            ],
        ];

        foreach ($permissionsByRole as $roleName => $permissions) {

            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => $guardName,
            ]);

            $permissionIds = [];

            foreach ($permissions as $name => $arName) {

                // 🔥 استخراج اسم الجروب
                $group = explode('.', $name)[0];

                $permission = Permission::firstOrCreate(
                    [
                        'name' => $name,
                        'guard_name' => $guardName,
                    ],
                    [
                        'group' => $group,
                        'group_ar' => $groupTranslations[$group] ?? $group,
                        'ar_name' => $arName,
                    ]
                );

                // 🔥 تحديث لو موجود قبل كده
                $permission->update([
                    'ar_name' => $arName,
                    'group_ar' => $groupTranslations[$group] ?? $group,
                ]);

                $permissionIds[] = $permission->id;
            }

            $role->syncPermissions($permissionIds);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}