<?php

namespace Database\Seeders;

use App\Models\Nationality;
use Illuminate\Database\Seeder;

class NationalitySeeder extends Seeder
{
    public function run(): void
    {
        $nationalities = [
            ['name' => 'مصري'],
            ['name' => 'سعودي'],
            ['name' => 'إماراتي'],
            ['name' => 'كويتي'],
            ['name' => 'بحريني'],
            ['name' => 'قطري'],
            ['name' => 'عماني'],
            ['name' => 'أردني'],
            ['name' => 'لبناني'],
            ['name' => 'سوري'],
            ['name' => 'فلسطيني'],
            ['name' => 'عراقي'],
            ['name' => 'يمني'],
            ['name' => 'ليبي'],
            ['name' => 'تونسي'],
            ['name' => 'جزائري'],
            ['name' => 'مغربي'],
            ['name' => 'سوداني'],
            ['name' => 'أمريكي'],
            ['name' => 'بريطاني'],
            ['name' => 'فرنسي'],
            ['name' => 'ألماني'],
            ['name' => 'إيطالي'],
            ['name' => 'إسباني'],
            ['name' => 'هولندي'],
            ['name' => 'بلجيكي'],
            ['name' => 'سويسري'],
            ['name' => 'سويدي'],
            ['name' => 'نرويجي'],
            ['name' => 'دنماركي'],
            ['name' => 'فنلندي'],
            ['name' => 'روسي'],
            ['name' => 'صيني'],
            ['name' => 'ياباني'],
            ['name' => 'كوري جنوبي'],
            ['name' => 'هندي'],
            ['name' => 'باكستاني'],
            ['name' => 'بنغلاديشي'],
            ['name' => 'أفغاني'],
            ['name' => 'تركي'],
            ['name' => 'إيراني'],
            ['name' => 'إسرائيلي'],
            ['name' => 'أسترالي'],
            ['name' => 'كندي'],
            ['name' => 'برازيلي'],
            ['name' => 'أرجنتيني'],
            ['name' => 'مكسيكي'],
            ['name' => 'جنوب أفريقي'],
            ['name' => 'نيجيري'],
            ['name' => 'كينيا'],
            ['name' => 'إثيوبي'],
            ['name' => 'إريتري'],
            ['name' => 'صومالي'],
            ['name' => 'جيبوتي'],
            ['name' => 'تشادي'],
            ['name' => 'أوغندي'],
            ['name' => 'تنزاني'],
            ['name' => 'رواندي'],
            ['name' => 'بوروندي'],
            ['name' => 'كونغولي'],
            ['name' => 'كاميروني'],
            ['name' => 'غاني'],
            ['name' => 'سنغالي'],
            ['name' => 'مالي'],
            ['name' => 'موريتاني'],
            ['name' => 'ماليزي'],
            ['name' => 'إندونيسي'],
            ['name' => 'فلبيني'],
            ['name' => 'تايلندي'],
            ['name' => 'فيتنامي'],
            ['name' => 'سيريلانكي'],
        ];

        foreach ($nationalities as $nationality) {
            Nationality::updateOrCreate(
                ['name' => $nationality['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Nationalities seeded: ' . count($nationalities) . ' records');
    }
}
