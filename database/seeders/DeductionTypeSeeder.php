<?php

namespace Database\Seeders;

use App\Models\DeductionType;
use Illuminate\Database\Seeder;

class DeductionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $deductionTypes = [
            ['name' => 'خصم غياب'],
            ['name' => 'خصم تأخير'],
            ['name' => 'خصم انصراف مبكر'],
            ['name' => 'خصم سلفة'],
            ['name' => 'خصم قرض'],
            ['name' => 'خصم تأمينات'],
            ['name' => 'خصم ضرائب'],
            ['name' => 'خصم غياب بدون إذن'],
            ['name' => 'خصم مخالفات'],
            ['name' => 'خصم تلفيات'],
            ['name' => 'خصم استقطاع قضائي'],
            ['name' => 'خصم نقابي'],
            ['name' => 'خصم مكافأة'],
            ['name' => 'خصم إجازة بدون راتب'],
            ['name' => 'خصم كفالة'],
            ['name' => 'خصم خطأ مهني'],
            ['name' => 'خصم تعويضات'],
            ['name' => 'خصم تأخير مستندات'],
            ['name' => 'خصم استقالة'],
            ['name' => 'خصم فسخ عقد'],
        ];

        foreach ($deductionTypes as $type) {
            DeductionType::updateOrCreate(
                ['name' => $type['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Deduction Types seeded: ' . count($deductionTypes) . ' records');
    }
}
