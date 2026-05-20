<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            // الأقسام الطبية الرئيسية
            ['name' => 'قسم الطوارئ', 'number' => 'ER-001', 'description' => 'استقبال الحالات الطارئة والإسعافات الأولية'],
            ['name' => 'قسم الباطنة', 'number' => 'INT-002', 'description' => 'تشخيص وعلاج الأمراض الباطنية'],
            ['name' => 'قسم الجراحة العامة', 'number' => 'SUR-003', 'description' => 'العمليات الجراحية العامة'],
            ['name' => 'قسم الأطفال', 'number' => 'PED-004', 'description' => 'علاج ورعاية الأطفال'],
            ['name' => 'قسم النساء والتوليد', 'number' => 'GYN-005', 'description' => 'رعاية الأمومة والولادة'],
            ['name' => 'قسم العظام', 'number' => 'ORT-006', 'description' => 'علاج الكسور وجراحة العظام'],
            ['name' => 'قسم القلب', 'number' => 'CAR-007', 'description' => 'تشخيص وعلاج أمراض القلب'],
            ['name' => 'قسم المخ والأعصاب', 'number' => 'NEU-008', 'description' => 'علاج أمراض المخ والأعصاب'],
            ['name' => 'قسم المسالك البولية', 'number' => 'URO-009', 'description' => 'علاج أمراض المسالك البولية'],
            ['name' => 'قسم الأنف والأذن والحنجرة', 'number' => 'ENT-010', 'description' => 'تشخيص وعلاج أمراض الأنف والأذن والحنجرة'],
            ['name' => 'قسم العيون', 'number' => 'OPH-011', 'description' => 'علاج أمراض العيون'],
            ['name' => 'قسم الجلدية', 'number' => 'DER-012', 'description' => 'علاج الأمراض الجلدية'],
            ['name' => 'قسم الأسنان', 'number' => 'DEN-013', 'description' => 'علاج الأسنان واللثة'],
            ['name' => 'قسم الصيدلة', 'number' => 'PHA-014', 'description' => 'صرف الأدوية وتوفيرها'],
            ['name' => 'قسم المختبر', 'number' => 'LAB-015', 'description' => 'التحاليل الطبية'],
            ['name' => 'قسم الأشعة', 'number' => 'RAD-016', 'description' => 'الأشعة التشخيصية والتداخلية'],
            ['name' => 'قسم العلاج الطبيعي', 'number' => 'PHT-017', 'description' => 'علاج طبيعي وتأهيل'],
            ['name' => 'قسم التخدير', 'number' => 'ANE-018', 'description' => 'خدمات التخدير للعمليات'],
            ['name' => 'قسم العناية المركزة', 'number' => 'ICU-019', 'description' => 'رعاية الحالات الحرجة'],
            ['name' => 'قسم الحضانات', 'number' => 'NICU-020', 'description' => 'رعاية الأطفال حديثي الولادة'],

            // الأقسام الإدارية
            ['name' => 'قسم الموارد البشرية', 'number' => 'HR-101', 'description' => 'إدارة شؤون الموظفين'],
            ['name' => 'قسم المحاسبة', 'number' => 'ACC-102', 'description' => 'الإدارة المالية والمحاسبية'],
            ['name' => 'قسم المشتريات', 'number' => 'PRO-103', 'description' => 'شراء وتوريد المستلزمات'],
            ['name' => 'قسم علاقات عامة', 'number' => 'PR-104', 'description' => 'التواصل مع المرضى والجمهور'],
            ['name' => 'قسم تكنولوجيا المعلومات', 'number' => 'IT-105', 'description' => 'الدعم التقني والشبكات'],
            ['name' => 'قسم الاستقبال', 'number' => 'REC-106', 'description' => 'استقبال المرضى والتوجيه'],
            ['name' => 'قسم التغذية', 'number' => 'NUT-107', 'description' => 'تغذية المرضى والعاملين'],
            ['name' => 'قسم النظافة', 'number' => 'CLN-108', 'description' => 'النظافة والتعقيم'],
            ['name' => 'قسم الأمن', 'number' => 'SEC-109', 'description' => 'تأمين المستشفى'],
            ['name' => 'قسم الصيانة', 'number' => 'MAINT-110', 'description' => 'صيانة المعدات والأجهزة'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                [
                    'name' => $department['name'],
                    'company_id' => 1
                ],
                [
                    'number' => $department['number'],
                    'description' => $department['description'],
                    'status' => 1,
                    'company_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Departments seeded: ' . count($departments) . ' records');
    }
}
