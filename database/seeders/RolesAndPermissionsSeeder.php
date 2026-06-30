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
            'services' => 'الخدمات',
            'sources' => 'مصدر البيان',
            'close-reason-classify' => 'سبب إغلاق البيان',
            

            
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

                "reports.index" => " عرض صفحة التقارير",
                "reports.view-report-central-report" =>  ' تقرير مركزى لجميع الشكاوى والاستفسارات الوارده ' ,
                "reports.view-report-complaint-percentage-report" =>  ' تقرير بأعلى الشكاوى الوارده من حيث التصنيف ',
                "reports.view-report-complaint-saved-reasons-report" =>  ' تقرير بتفصيل أسباب حفظ الشكاوى الوارده ',
                "reports.view-report-compare-request-type-between-years" => ' تقرير سنوى بعدد الشكاوى والاستفسارات الوارده ',
                "reports.view-report-complaints-inquiries-summary-by-source" => ' بيان مختصر بإجمالى عدد ( الشكاوى / الإستفسارات ) بالنسبه للمصدر ',
                "reports.view-report-offices-complaints-and-inquiries-summary-report" => ' تقرير مركزى عن عدد الشكاوى والإستفسارات الواردة للمكاتب ',
                "reports.view-report-offices-saved-complaints-count-report" =>  ' تقرير مركزى عن عدد الشكاوى المحفوظ بالنسبة للمكاتب ',
                "reports.view-report-annual-sources-comparison" => ' مقارنه سنويه للمصادر فى الشكاوى والاستفسارات',

                "complaints.index" => "عرض الشكاوى",
                "complaints.create" => "إضافة بيان",
                "complaints.store" => "حفظ البيان",
                "complaints.update" => "تحديث البيان",
                "complaints.edit" => "تعديل البيان",
                "complaints.reply" => "الرد على البيان",
                "complaints.destroy" => "حذف البيان",
                "complaints.show" => "عرض البيان",
                "complaints.duplicate" => " تكرار للبيان",
                "complaints.duplicate.create" => "إضافة تكرار للبيان",
                "complaints.duplicate.store" => "حفظ تكرار للبيان",
                "complaints.duplicates.index" => "عرض تكرارات البيان",

                

                "responses.index" => "عرض الردود",
                "responses.create" => "إضافة رد",
                "responses.store" => "حفظ الرد",
                "responses.update" => "تحديث الرد",
                "responses.edit" => "تعديل الرد",
                "responses.reply" => "الرد",
                "responses.destroy" => "حذف الرد",
                "responses.show" => "عرض الرد",
                "responses.data" => "بيانات الردود",

                "services.index" => "عرض الخدمات",
                "services.create" => "إضافة خدمة",
                "services.store" => "حفظ الخدمة",
                "services.update" => "تحديث الخدمة",
                "services.edit" => "تعديل الخدمة",
                "services.destroy" => "حذف الخدمة",
                "services.show" => "عرض الخدمة",
                "services.data" => "بيانات الخدمات",

                "sources.index" => "عرض مصادر الشكاوي",
                "sources.create" => "إضافة مصدر بيان",
                "sources.store" => "حفظ مصدر بيان",
                "sources.update" => "تحديث مصدر بيان",
                "sources.edit" => "تعديل مصدر بيان",
                "sources.destroy" => "حذف مصدر بيان",
                "sources.show" => "عرض مصدر بيان",
                "sources.data" => "بيانات مصادر الشكاوي",

                "close-reason-classify.index" => "عرض أسباب إغلاق الشكاوي",
                "close-reason-classify.create" => "إضافة سبب إغلاق بيان",
                "close-reason-classify.store" => "حفظ سبب إغلاق بيان",
                "close-reason-classify.update" => "تحديث سبب إغلاق بيان",
                "close-reason-classify.edit" => "تعديل سبب إغلاق بيان",
                "close-reason-classify.destroy" => "حذف سبب إغلاق بيان",
                "close-reason-classify.show" => "عرض سبب إغلاق بيان",
                "close-reason-classify.data" => "بيانات أسباب إغلاق الشكاوي",

              


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

            if ($roleName === 'admin') {
                $adminUser = User::where('userID' , 'Nadine.essam' )->first();
                if ($adminUser) {
                    $adminUser->assignRole($role);
                }

            }

        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}