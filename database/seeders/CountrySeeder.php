<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'مصر'],
            ['name' => 'السعودية'],
            ['name' => 'الإمارات'],
            ['name' => 'الكويت'],
            ['name' => 'البحرين'],
            ['name' => 'قطر'],
            ['name' => 'عمان'],
            ['name' => 'الأردن'],
            ['name' => 'لبنان'],
            ['name' => 'سوريا'],
            ['name' => 'فلسطين'],
            ['name' => 'العراق'],
            ['name' => 'اليمن'],
            ['name' => 'ليبيا'],
            ['name' => 'تونس'],
            ['name' => 'الجزائر'],
            ['name' => 'المغرب'],
            ['name' => 'السودان'],
            ['name' => 'موريتانيا'],
            ['name' => 'الصومال'],
            ['name' => 'جيبوتي'],
            ['name' => 'جزر القمر'],
            ['name' => 'الولايات المتحدة'],
            ['name' => 'بريطانيا'],
            ['name' => 'فرنسا'],
            ['name' => 'ألمانيا'],
            ['name' => 'إيطاليا'],
            ['name' => 'إسبانيا'],
            ['name' => 'هولندا'],
            ['name' => 'بلجيكا'],
            ['name' => 'سويسرا'],
            ['name' => 'السويد'],
            ['name' => 'النرويج'],
            ['name' => 'الدنمارك'],
            ['name' => 'فنلندا'],
            ['name' => 'روسيا'],
            ['name' => 'الصين'],
            ['name' => 'اليابان'],
            ['name' => 'كوريا الجنوبية'],
            ['name' => 'الهند'],
            ['name' => 'باكستان'],
            ['name' => 'بنغلاديش'],
            ['name' => 'تركيا'],
            ['name' => 'إيران'],
            ['name' => 'أستراليا'],
            ['name' => 'كندا'],
            ['name' => 'البرازيل'],
            ['name' => 'الأرجنتين'],
            ['name' => 'المكسيك'],
            ['name' => 'جنوب أفريقيا'],
            ['name' => 'نيجيريا'],
            ['name' => 'كينيا'],
            ['name' => 'إثيوبيا'],
            ['name' => 'إريتريا'],
            ['name' => 'تشاد'],
            ['name' => 'أوغندا'],
            ['name' => 'تنزانيا'],
            ['name' => 'رواندا'],
            ['name' => 'بوروندي'],
            ['name' => 'الكونغو'],
            ['name' => 'الكاميرون'],
            ['name' => 'غانا'],
            ['name' => 'السنغال'],
            ['name' => 'مالي'],
            ['name' => 'ماليزيا'],
            ['name' => 'إندونيسيا'],
            ['name' => 'الفلبين'],
            ['name' => 'تايلاند'],
            ['name' => 'فيتنام'],
            ['name' => 'سريلانكا'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['name' => $country['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Countries seeded: ' . count($countries) . ' records');
    }
}
