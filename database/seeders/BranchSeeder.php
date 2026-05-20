<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Branche;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'الفرع الرئيسي - مدينة نصر',
                'address' => 'شارع النيل، مدينة نصر، القاهرة',
                'phone' => '0223456789',
                'email' => 'main@elnourhospital.com',
            ],
            [
                'name' => 'فرع المهندسين',
                'address' => 'شارع السودان، المهندسين، الجيزة',
                'phone' => '0234567890',
                'email' => 'mohandseen@elnourhospital.com',
            ],
            [
                'name' => 'فرع المعادي',
                'address' => 'شارع 9، المعادي، القاهرة',
                'phone' => '0223456790',
                'email' => 'maadi@elnourhospital.com',
            ],
            [
                'name' => 'فرع الإسكندرية',
                'address' => 'شارع سموحة، الإسكندرية',
                'phone' => '0345678901',
                'email' => 'alex@elnourhospital.com',
            ],
            [
                'name' => 'فرع الجيزة',
                'address' => 'شارع الهرم، الجيزة',
                'phone' => '0234567891',
                'email' => 'giza@elnourhospital.com',
            ],
            [
                'name' => 'فرع شبرا',
                'address' => 'شارع شبرا، القاهرة',
                'phone' => '0223456792',
                'email' => 'shobra@elnourhospital.com',
            ],
            [
                'name' => 'فرع حلوان',
                'address' => 'شارع حلوان، القاهرة',
                'phone' => '0223456793',
                'email' => 'helwan@elnourhospital.com',
            ],
            [
                'name' => 'فرع العبور',
                'address' => 'مدينة العبور، القليوبية',
                'phone' => '0223456794',
                'email' => 'obour@elnourhospital.com',
            ],
            [
                'name' => 'فرع السادس من أكتوبر',
                'address' => 'السادس من أكتوبر، الجيزة',
                'phone' => '0234567895',
                'email' => 'october@elnourhospital.com',
            ],
            [
                'name' => 'فرع بورسعيد',
                'address' => 'شارع بورسعيد، بورسعيد',
                'phone' => '0663456796',
                'email' => 'portsaid@elnourhospital.com',
            ],
            [
                'name' => 'فرع السويس',
                'address' => 'شارع السويس، السويس',
                'phone' => '0623456797',
                'email' => 'suez@elnourhospital.com',
            ],
            [
                'name' => 'فرع طنطا',
                'address' => 'شارع طنطا، الغربية',
                'phone' => '0403456798',
                'email' => 'tanta@elnourhospital.com',
            ],
            [
                'name' => 'فرع المنصورة',
                'address' => 'شارع المنصورة، الدقهلية',
                'phone' => '0503456799',
                'email' => 'mansoura@elnourhospital.com',
            ],
            [
                'name' => 'فرع أسيوط',
                'address' => 'شارع أسيوط، أسيوط',
                'phone' => '0883456800',
                'email' => 'assiut@elnourhospital.com',
            ],
            [
                'name' => 'فرع الأقصر',
                'address' => 'شارع الأقصر، الأقصر',
                'phone' => '0953456801',
                'email' => 'luxor@elnourhospital.com',
            ],
            [
                'name' => 'فرع أسوان',
                'address' => 'شارع أسوان، أسوان',
                'phone' => '0973456802',
                'email' => 'aswan@elnourhospital.com',
            ],
            [
                'name' => 'فرع الغردقة',
                'address' => 'شارع الغردقة، البحر الأحمر',
                'phone' => '0653456803',
                'email' => 'hurghada@elnourhospital.com',
            ],
            [
                'name' => 'فرع الإسماعيلية',
                'address' => 'شارع الإسماعيلية، الإسماعيلية',
                'phone' => '0643456804',
                'email' => 'ismailia@elnourhospital.com',
            ],
            [
                'name' => 'فرع دمياط',
                'address' => 'شارع دمياط، دمياط',
                'phone' => '0573456805',
                'email' => 'damietta@elnourhospital.com',
            ],
            [
                'name' => 'فرع بنها',
                'address' => 'شارع بنها، القليوبية',
                'phone' => '0133456806',
                'email' => 'banha@elnourhospital.com',
            ],
        ];

        foreach ($branches as $branch) {
            Branche::updateOrCreate(
                ['name' => $branch['name'], 'company_id' => 1],
                [
                    'address' => $branch['address'],
                    'phone' => $branch['phone'],
                    'email' => $branch['email'],
                    'status' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'company_id' => 1,
                ]
            );
        }

        $this->command->info('✅ Branches seeded: ' . count($branches) . ' records');
    }
}
