<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [];

        // ==================== مصر (محافظات 1-27) ====================

        // القاهرة (1)
        $cities[] = ['name' => 'مدينة نصر', 'governorate_id' => 1];
        $cities[] = ['name' => 'مصر الجديدة', 'governorate_id' => 1];
        $cities[] = ['name' => 'العباسية', 'governorate_id' => 1];
        $cities[] = ['name' => 'شبرا', 'governorate_id' => 1];
        $cities[] = ['name' => 'روض الفرج', 'governorate_id' => 1];
        $cities[] = ['name' => 'السيدة زينب', 'governorate_id' => 1];
        $cities[] = ['name' => 'الوايلي', 'governorate_id' => 1];
        $cities[] = ['name' => 'باب الشعرية', 'governorate_id' => 1];
        $cities[] = ['name' => 'الأزبكية', 'governorate_id' => 1];
        $cities[] = ['name' => 'عابدين', 'governorate_id' => 1];
        $cities[] = ['name' => 'بولاق', 'governorate_id' => 1];
        $cities[] = ['name' => 'قصر النيل', 'governorate_id' => 1];
        $cities[] = ['name' => 'المطرية', 'governorate_id' => 1];
        $cities[] = ['name' => 'عين شمس', 'governorate_id' => 1];
        $cities[] = ['name' => 'الزيتون', 'governorate_id' => 1];
        $cities[] = ['name' => 'حدائق القبة', 'governorate_id' => 1];
        $cities[] = ['name' => 'المرج', 'governorate_id' => 1];
        $cities[] = ['name' => 'مدينة السلام', 'governorate_id' => 1];
        $cities[] = ['name' => 'النزهة', 'governorate_id' => 1];
        $cities[] = ['name' => 'الرحاب', 'governorate_id' => 1];
        $cities[] = ['name' => 'مدينتي', 'governorate_id' => 1];
        $cities[] = ['name' => 'التجمع الخامس', 'governorate_id' => 1];
        $cities[] = ['name' => 'الشروق', 'governorate_id' => 1];
        $cities[] = ['name' => 'بدر', 'governorate_id' => 1];
        $cities[] = ['name' => 'العبور', 'governorate_id' => 1];
        $cities[] = ['name' => 'حلوان', 'governorate_id' => 1];
        $cities[] = ['name' => 'المعادي', 'governorate_id' => 1];
        $cities[] = ['name' => 'طرة', 'governorate_id' => 1];
        $cities[] = ['name' => 'البساتين', 'governorate_id' => 1];
        $cities[] = ['name' => 'دار السلام', 'governorate_id' => 1];
        $cities[] = ['name' => 'التبين', 'governorate_id' => 1];

        // الجيزة (2)
        $cities[] = ['name' => 'الجيزة', 'governorate_id' => 2];
        $cities[] = ['name' => 'العجوزة', 'governorate_id' => 2];
        $cities[] = ['name' => 'الدقي', 'governorate_id' => 2];
        $cities[] = ['name' => 'المهندسين', 'governorate_id' => 2];
        $cities[] = ['name' => 'الهرم', 'governorate_id' => 2];
        $cities[] = ['name' => 'فيصل', 'governorate_id' => 2];
        $cities[] = ['name' => 'البواردي', 'governorate_id' => 2];
        $cities[] = ['name' => 'أكتوبر', 'governorate_id' => 2];
        $cities[] = ['name' => 'الشيخ زايد', 'governorate_id' => 2];
        $cities[] = ['name' => 'حدائق أكتوبر', 'governorate_id' => 2];
        $cities[] = ['name' => 'الواحات البحرية', 'governorate_id' => 2];
        $cities[] = ['name' => 'البدرشين', 'governorate_id' => 2];
        $cities[] = ['name' => 'العياط', 'governorate_id' => 2];
        $cities[] = ['name' => 'أبو النمرس', 'governorate_id' => 2];
        $cities[] = ['name' => 'كرداسة', 'governorate_id' => 2];
        $cities[] = ['name' => 'أوسيم', 'governorate_id' => 2];
        $cities[] = ['name' => 'منشأة القناطر', 'governorate_id' => 2];
        $cities[] = ['name' => 'صفط اللبن', 'governorate_id' => 2];

        // الإسكندرية (3)
        $cities[] = ['name' => 'الإسكندرية', 'governorate_id' => 3];
        $cities[] = ['name' => 'المنتزة', 'governorate_id' => 3];
        $cities[] = ['name' => 'شرق', 'governorate_id' => 3];
        $cities[] = ['name' => 'وسط', 'governorate_id' => 3];
        $cities[] = ['name' => 'غرب', 'governorate_id' => 3];
        $cities[] = ['name' => 'الجمرك', 'governorate_id' => 3];
        $cities[] = ['name' => 'العجمي', 'governorate_id' => 3];
        $cities[] = ['name' => 'برج العرب', 'governorate_id' => 3];
        $cities[] = ['name' => 'الدخيلة', 'governorate_id' => 3];
        $cities[] = ['name' => 'سيدي بشر', 'governorate_id' => 3];
        $cities[] = ['name' => 'كليوباترا', 'governorate_id' => 3];
        $cities[] = ['name' => 'الرمل', 'governorate_id' => 3];
        $cities[] = ['name' => 'محرم بك', 'governorate_id' => 3];
        $cities[] = ['name' => 'إبراهيمية', 'governorate_id' => 3];
        $cities[] = ['name' => 'زيزينيا', 'governorate_id' => 3];
        $cities[] = ['name' => 'السيوف', 'governorate_id' => 3];
        $cities[] = ['name' => 'الورديان', 'governorate_id' => 3];
        $cities[] = ['name' => 'القباري', 'governorate_id' => 3];

        // الدقهلية (4)
        $cities[] = ['name' => 'المنصورة', 'governorate_id' => 4];
        $cities[] = ['name' => 'طلخا', 'governorate_id' => 4];
        $cities[] = ['name' => 'ميت غمر', 'governorate_id' => 4];
        $cities[] = ['name' => 'دكرنس', 'governorate_id' => 4];
        $cities[] = ['name' => 'أجا', 'governorate_id' => 4];
        $cities[] = ['name' => 'السنبلاوين', 'governorate_id' => 4];
        $cities[] = ['name' => 'المنزلة', 'governorate_id' => 4];
        $cities[] = ['name' => 'تمي الأمديد', 'governorate_id' => 4];
        $cities[] = ['name' => 'ميت سلسيل', 'governorate_id' => 4];
        $cities[] = ['name' => 'نبروه', 'governorate_id' => 4];
        $cities[] = ['name' => 'شربين', 'governorate_id' => 4];
        $cities[] = ['name' => 'بني عبيد', 'governorate_id' => 4];
        $cities[] = ['name' => 'الستاموني', 'governorate_id' => 4];
        $cities[] = ['name' => 'جمصة', 'governorate_id' => 4];
        $cities[] = ['name' => 'بلقاس', 'governorate_id' => 4];

        // الشرقية (5)
        $cities[] = ['name' => 'الزقازيق', 'governorate_id' => 5];
        $cities[] = ['name' => 'بلبيس', 'governorate_id' => 5];
        $cities[] = ['name' => 'منيا القمح', 'governorate_id' => 5];
        $cities[] = ['name' => 'أبو كبير', 'governorate_id' => 5];
        $cities[] = ['name' => 'ههيا', 'governorate_id' => 5];
        $cities[] = ['name' => 'فاقوس', 'governorate_id' => 5];
        $cities[] = ['name' => 'الصالحية', 'governorate_id' => 5];
        $cities[] = ['name' => 'الحسينية', 'governorate_id' => 5];
        $cities[] = ['name' => 'كفر صقر', 'governorate_id' => 5];
        $cities[] = ['name' => 'مشتول السوق', 'governorate_id' => 5];
        $cities[] = ['name' => 'الإبراهيمية', 'governorate_id' => 5];
        $cities[] = ['name' => 'ديرب نجم', 'governorate_id' => 5];
        $cities[] = ['name' => 'القرين', 'governorate_id' => 5];
        $cities[] = ['name' => 'أولاد صقر', 'governorate_id' => 5];

        // الغربية (6)
        $cities[] = ['name' => 'طنطا', 'governorate_id' => 6];
        $cities[] = ['name' => 'المحلة الكبرى', 'governorate_id' => 6];
        $cities[] = ['name' => 'كفر الزيات', 'governorate_id' => 6];
        $cities[] = ['name' => 'زفتى', 'governorate_id' => 6];
        $cities[] = ['name' => 'السنطة', 'governorate_id' => 6];
        $cities[] = ['name' => 'قطور', 'governorate_id' => 6];
        $cities[] = ['name' => 'بسيون', 'governorate_id' => 6];
        $cities[] = ['name' => 'سمنود', 'governorate_id' => 6];

        // المنوفية (7)
        $cities[] = ['name' => 'شبين الكوم', 'governorate_id' => 7];
        $cities[] = ['name' => 'السادات', 'governorate_id' => 7];
        $cities[] = ['name' => 'منوف', 'governorate_id' => 7];
        $cities[] = ['name' => 'أشمون', 'governorate_id' => 7];
        $cities[] = ['name' => 'الباجور', 'governorate_id' => 7];
        $cities[] = ['name' => 'قويسنا', 'governorate_id' => 7];
        $cities[] = ['name' => 'بركة السبع', 'governorate_id' => 7];
        $cities[] = ['name' => 'تلا', 'governorate_id' => 7];
        $cities[] = ['name' => 'الشهداء', 'governorate_id' => 7];

        // القليوبية (8)
        $cities[] = ['name' => 'بنها', 'governorate_id' => 8];
        $cities[] = ['name' => 'شبرا الخيمة', 'governorate_id' => 8];
        $cities[] = ['name' => 'قليوب', 'governorate_id' => 8];
        $cities[] = ['name' => 'القناطر الخيرية', 'governorate_id' => 8];
        $cities[] = ['name' => 'الخانكة', 'governorate_id' => 8];
        $cities[] = ['name' => 'كفر شكر', 'governorate_id' => 8];
        $cities[] = ['name' => 'طوخ', 'governorate_id' => 8];
        $cities[] = ['name' => 'قها', 'governorate_id' => 8];
        $cities[] = ['name' => 'خصوص', 'governorate_id' => 8];

        // كفر الشيخ (9)
        $cities[] = ['name' => 'كفر الشيخ', 'governorate_id' => 9];
        $cities[] = ['name' => 'دسوق', 'governorate_id' => 9];
        $cities[] = ['name' => 'فوه', 'governorate_id' => 9];
        $cities[] = ['name' => 'مطوبس', 'governorate_id' => 9];
        $cities[] = ['name' => 'بيلا', 'governorate_id' => 9];
        $cities[] = ['name' => 'الرياض', 'governorate_id' => 9];
        $cities[] = ['name' => 'سيدي سالم', 'governorate_id' => 9];
        $cities[] = ['name' => 'قلين', 'governorate_id' => 9];
        $cities[] = ['name' => 'الحامول', 'governorate_id' => 9];

        // دمياط (10)
        $cities[] = ['name' => 'دمياط', 'governorate_id' => 10];
        $cities[] = ['name' => 'دمياط الجديدة', 'governorate_id' => 10];
        $cities[] = ['name' => 'كفر سعد', 'governorate_id' => 10];
        $cities[] = ['name' => 'فارسكور', 'governorate_id' => 10];
        $cities[] = ['name' => 'الروضة', 'governorate_id' => 10];
        $cities[] = ['name' => 'عزبة البرج', 'governorate_id' => 10];
        $cities[] = ['name' => 'ميت أبو غالب', 'governorate_id' => 10];

        // بورسعيد (11)
        $cities[] = ['name' => 'بورسعيد', 'governorate_id' => 11];
        $cities[] = ['name' => 'بورفؤاد', 'governorate_id' => 11];
        $cities[] = ['name' => 'الزهور', 'governorate_id' => 11];
        $cities[] = ['name' => 'العرب', 'governorate_id' => 11];
        $cities[] = ['name' => 'الضواحي', 'governorate_id' => 11];

        // الإسماعيلية (12)
        $cities[] = ['name' => 'الإسماعيلية', 'governorate_id' => 12];
        $cities[] = ['name' => 'الإسماعيلية الجديدة', 'governorate_id' => 12];
        $cities[] = ['name' => 'فايد', 'governorate_id' => 12];
        $cities[] = ['name' => 'القنطرة', 'governorate_id' => 12];
        $cities[] = ['name' => 'التل الكبير', 'governorate_id' => 12];
        $cities[] = ['name' => 'أبو صوير', 'governorate_id' => 12];
        $cities[] = ['name' => 'القصاصين', 'governorate_id' => 12];

        // السويس (13)
        $cities[] = ['name' => 'السويس', 'governorate_id' => 13];
        $cities[] = ['name' => 'الأربعين', 'governorate_id' => 13];
        $cities[] = ['name' => 'عتاقة', 'governorate_id' => 13];
        $cities[] = ['name' => 'الجناين', 'governorate_id' => 13];
        $cities[] = ['name' => 'فيصل', 'governorate_id' => 13];

        // شمال سيناء (14)
        $cities[] = ['name' => 'العريش', 'governorate_id' => 14];
        $cities[] = ['name' => 'الشيخ زويد', 'governorate_id' => 14];
        $cities[] = ['name' => 'رفح', 'governorate_id' => 14];
        $cities[] = ['name' => 'بئر العبد', 'governorate_id' => 14];
        $cities[] = ['name' => 'الحسنة', 'governorate_id' => 14];

        // جنوب سيناء (15)
        $cities[] = ['name' => 'الطور', 'governorate_id' => 15];
        $cities[] = ['name' => 'شرم الشيخ', 'governorate_id' => 15];
        $cities[] = ['name' => 'دهب', 'governorate_id' => 15];
        $cities[] = ['name' => 'نويبع', 'governorate_id' => 15];
        $cities[] = ['name' => 'طابا', 'governorate_id' => 15];
        $cities[] = ['name' => 'سانت كاترين', 'governorate_id' => 15];
        $cities[] = ['name' => 'رأس سدر', 'governorate_id' => 15];
        $cities[] = ['name' => 'أبو رديس', 'governorate_id' => 15];

        // الأقصر (16)
        $cities[] = ['name' => 'الأقصر', 'governorate_id' => 16];
        $cities[] = ['name' => 'البياضية', 'governorate_id' => 16];
        $cities[] = ['name' => 'الطود', 'governorate_id' => 16];
        $cities[] = ['name' => 'إسنا', 'governorate_id' => 16];
        $cities[] = ['name' => 'أرمنت', 'governorate_id' => 16];
        $cities[] = ['name' => 'القرنة', 'governorate_id' => 16];
        $cities[] = ['name' => 'مدينة طيبة', 'governorate_id' => 16];

        // أسوان (17)
        $cities[] = ['name' => 'أسوان', 'governorate_id' => 17];
        $cities[] = ['name' => 'أسوان الجديدة', 'governorate_id' => 17];
        $cities[] = ['name' => 'إدفو', 'governorate_id' => 17];
        $cities[] = ['name' => 'كوم أمبو', 'governorate_id' => 17];
        $cities[] = ['name' => 'نصر النوبة', 'governorate_id' => 17];
        $cities[] = ['name' => 'دراو', 'governorate_id' => 17];
        $cities[] = ['name' => 'كلابشة', 'governorate_id' => 17];
        $cities[] = ['name' => 'أبو سمبل', 'governorate_id' => 17];

        // قنا (18)
        $cities[] = ['name' => 'قنا', 'governorate_id' => 18];
        $cities[] = ['name' => 'قنا الجديدة', 'governorate_id' => 18];
        $cities[] = ['name' => 'نجع حمادي', 'governorate_id' => 18];
        $cities[] = ['name' => 'دشنا', 'governorate_id' => 18];
        $cities[] = ['name' => 'الوقف', 'governorate_id' => 18];
        $cities[] = ['name' => 'فرشوط', 'governorate_id' => 18];
        $cities[] = ['name' => 'نقادة', 'governorate_id' => 18];
        $cities[] = ['name' => 'قوص', 'governorate_id' => 18];
        $cities[] = ['name' => 'أبو تشت', 'governorate_id' => 18];

        // سوهاج (19)
        $cities[] = ['name' => 'سوهاج', 'governorate_id' => 19];
        $cities[] = ['name' => 'سوهاج الجديدة', 'governorate_id' => 19];
        $cities[] = ['name' => 'أخميم', 'governorate_id' => 19];
        $cities[] = ['name' => 'البلينا', 'governorate_id' => 19];
        $cities[] = ['name' => 'المراغة', 'governorate_id' => 19];
        $cities[] = ['name' => 'المنشاة', 'governorate_id' => 19];
        $cities[] = ['name' => 'دار السلام', 'governorate_id' => 19];
        $cities[] = ['name' => 'جرجا', 'governorate_id' => 19];
        $cities[] = ['name' => 'طهطا', 'governorate_id' => 19];
        $cities[] = ['name' => 'طما', 'governorate_id' => 19];
        $cities[] = ['name' => 'جهينة', 'governorate_id' => 19];
        $cities[] = ['name' => 'ساقلته', 'governorate_id' => 19];

        // أسيوط (20)
        $cities[] = ['name' => 'أسيوط', 'governorate_id' => 20];
        $cities[] = ['name' => 'أسيوط الجديدة', 'governorate_id' => 20];
        $cities[] = ['name' => 'ديروط', 'governorate_id' => 20];
        $cities[] = ['name' => 'منفلوط', 'governorate_id' => 20];
        $cities[] = ['name' => 'القوصية', 'governorate_id' => 20];
        $cities[] = ['name' => 'أبو تيج', 'governorate_id' => 20];
        $cities[] = ['name' => 'الغنايم', 'governorate_id' => 20];
        $cities[] = ['name' => 'ساحل سليم', 'governorate_id' => 20];
        $cities[] = ['name' => 'البداري', 'governorate_id' => 20];
        $cities[] = ['name' => 'صدفا', 'governorate_id' => 20];

        // المنيا (21)
        $cities[] = ['name' => 'المنيا', 'governorate_id' => 21];
        $cities[] = ['name' => 'المنيا الجديدة', 'governorate_id' => 21];
        $cities[] = ['name' => 'ملوي', 'governorate_id' => 21];
        $cities[] = ['name' => 'بني مزار', 'governorate_id' => 21];
        $cities[] = ['name' => 'مطاي', 'governorate_id' => 21];
        $cities[] = ['name' => 'سمالوط', 'governorate_id' => 21];
        $cities[] = ['name' => 'أبو قرقاص', 'governorate_id' => 21];
        $cities[] = ['name' => 'عدوة', 'governorate_id' => 21];
        $cities[] = ['name' => 'مغاغة', 'governorate_id' => 21];

        // بني سويف (22)
        $cities[] = ['name' => 'بني سويف', 'governorate_id' => 22];
        $cities[] = ['name' => 'بني سويف الجديدة', 'governorate_id' => 22];
        $cities[] = ['name' => 'الواسطى', 'governorate_id' => 22];
        $cities[] = ['name' => 'ناصر', 'governorate_id' => 22];
        $cities[] = ['name' => 'إهناسيا', 'governorate_id' => 22];
        $cities[] = ['name' => 'ببا', 'governorate_id' => 22];
        $cities[] = ['name' => 'الفشن', 'governorate_id' => 22];
        $cities[] = ['name' => 'سمسطا', 'governorate_id' => 22];

        // الفيوم (23)
        $cities[] = ['name' => 'الفيوم', 'governorate_id' => 23];
        $cities[] = ['name' => 'الفيوم الجديدة', 'governorate_id' => 23];
        $cities[] = ['name' => 'طامية', 'governorate_id' => 23];
        $cities[] = ['name' => 'سنورس', 'governorate_id' => 23];
        $cities[] = ['name' => 'إطسا', 'governorate_id' => 23];
        $cities[] = ['name' => 'يوسف الصديق', 'governorate_id' => 23];
        $cities[] = ['name' => 'أبشواي', 'governorate_id' => 23];

        // مطروح (24)
        $cities[] = ['name' => 'مرسى مطروح', 'governorate_id' => 24];
        $cities[] = ['name' => 'الضبعة', 'governorate_id' => 24];
        $cities[] = ['name' => 'العلمين', 'governorate_id' => 24];
        $cities[] = ['name' => 'سيدي براني', 'governorate_id' => 24];
        $cities[] = ['name' => 'الحمام', 'governorate_id' => 24];
        $cities[] = ['name' => 'النجيلة', 'governorate_id' => 24];

        // الوادي الجديد (25)
        $cities[] = ['name' => 'الخارجة', 'governorate_id' => 25];
        $cities[] = ['name' => 'الداخلة', 'governorate_id' => 25];
        $cities[] = ['name' => 'باريس', 'governorate_id' => 25];
        $cities[] = ['name' => 'بلاط', 'governorate_id' => 25];
        $cities[] = ['name' => 'الفرافرة', 'governorate_id' => 25];

        // البحر الأحمر (26)
        $cities[] = ['name' => 'الغردقة', 'governorate_id' => 26];
        $cities[] = ['name' => 'رأس غارب', 'governorate_id' => 26];
        $cities[] = ['name' => 'سفاجا', 'governorate_id' => 26];
        $cities[] = ['name' => 'القصير', 'governorate_id' => 26];
        $cities[] = ['name' => 'مرسى علم', 'governorate_id' => 26];
        $cities[] = ['name' => 'حلايب', 'governorate_id' => 26];
        $cities[] = ['name' => 'شلاتين', 'governorate_id' => 26];

        // ==================== السعودية (27-39) ====================

        // الرياض (27)
        $cities[] = ['name' => 'الرياض', 'governorate_id' => 27];
        $cities[] = ['name' => 'الخرج', 'governorate_id' => 27];
        $cities[] = ['name' => 'الدوادمي', 'governorate_id' => 27];
        $cities[] = ['name' => 'المجمعة', 'governorate_id' => 27];
        $cities[] = ['name' => 'القويعية', 'governorate_id' => 27];
        $cities[] = ['name' => 'الأفلاج', 'governorate_id' => 27];
        $cities[] = ['name' => 'وادي الدواسر', 'governorate_id' => 27];
        $cities[] = ['name' => 'الزلفي', 'governorate_id' => 27];
        $cities[] = ['name' => 'شقراء', 'governorate_id' => 27];
        $cities[] = ['name' => 'حريملاء', 'governorate_id' => 27];

        // مكة المكرمة (28)
        $cities[] = ['name' => 'مكة المكرمة', 'governorate_id' => 28];
        $cities[] = ['name' => 'جدة', 'governorate_id' => 28];
        $cities[] = ['name' => 'الطائف', 'governorate_id' => 28];
        $cities[] = ['name' => 'القنفذة', 'governorate_id' => 28];
        $cities[] = ['name' => 'الليث', 'governorate_id' => 28];
        $cities[] = ['name' => 'رابغ', 'governorate_id' => 28];
        $cities[] = ['name' => 'خليص', 'governorate_id' => 28];
        $cities[] = ['name' => 'الجموم', 'governorate_id' => 28];
        $cities[] = ['name' => 'الكامل', 'governorate_id' => 28];
        $cities[] = ['name' => 'بحرة', 'governorate_id' => 28];

        // المدينة المنورة (29)
        $cities[] = ['name' => 'المدينة المنورة', 'governorate_id' => 29];
        $cities[] = ['name' => 'ينبع', 'governorate_id' => 29];
        $cities[] = ['name' => 'العلا', 'governorate_id' => 29];
        $cities[] = ['name' => 'المهد', 'governorate_id' => 29];
        $cities[] = ['name' => 'الحناكية', 'governorate_id' => 29];
        $cities[] = ['name' => 'بدر', 'governorate_id' => 29];
        $cities[] = ['name' => 'خيبر', 'governorate_id' => 29];

        // الشرقية (30)
        $cities[] = ['name' => 'الدمام', 'governorate_id' => 30];
        $cities[] = ['name' => 'الخبر', 'governorate_id' => 30];
        $cities[] = ['name' => 'الظهران', 'governorate_id' => 30];
        $cities[] = ['name' => 'القطيف', 'governorate_id' => 30];
        $cities[] = ['name' => 'الأحساء', 'governorate_id' => 30];
        $cities[] = ['name' => 'حفر الباطن', 'governorate_id' => 30];
        $cities[] = ['name' => 'الجبيل', 'governorate_id' => 30];
        $cities[] = ['name' => 'رأس تنورة', 'governorate_id' => 30];
        $cities[] = ['name' => 'بقيق', 'governorate_id' => 30];
        $cities[] = ['name' => 'النعيرية', 'governorate_id' => 30];
        $cities[] = ['name' => 'قرية العليا', 'governorate_id' => 30];
        $cities[] = ['name' => 'العديد', 'governorate_id' => 30];

        // عسير (31)
        $cities[] = ['name' => 'أبها', 'governorate_id' => 31];
        $cities[] = ['name' => 'خميس مشيط', 'governorate_id' => 31];
        $cities[] = ['name' => 'بيشة', 'governorate_id' => 31];
        $cities[] = ['name' => 'النماص', 'governorate_id' => 31];
        $cities[] = ['name' => 'محايل عسير', 'governorate_id' => 31];
        $cities[] = ['name' => 'ظهران الجنوب', 'governorate_id' => 31];
        $cities[] = ['name' => 'سراة عبيدة', 'governorate_id' => 31];
        $cities[] = ['name' => 'رجال المع', 'governorate_id' => 31];
        $cities[] = ['name' => 'بلقرن', 'governorate_id' => 31];
        $cities[] = ['name' => 'تنومة', 'governorate_id' => 31];

        // تبوك (32)
        $cities[] = ['name' => 'تبوك', 'governorate_id' => 32];
        $cities[] = ['name' => 'الوجه', 'governorate_id' => 32];
        $cities[] = ['name' => 'ضباء', 'governorate_id' => 32];
        $cities[] = ['name' => 'تيماء', 'governorate_id' => 32];
        $cities[] = ['name' => 'أملج', 'governorate_id' => 32];
        $cities[] = ['name' => 'حقل', 'governorate_id' => 32];

        // حائل (33)
        $cities[] = ['name' => 'حائل', 'governorate_id' => 33];
        $cities[] = ['name' => 'بقعاء', 'governorate_id' => 33];
        $cities[] = ['name' => 'الغزالة', 'governorate_id' => 33];
        $cities[] = ['name' => 'الشنان', 'governorate_id' => 33];
        $cities[] = ['name' => 'الحائط', 'governorate_id' => 33];
        $cities[] = ['name' => 'السليمي', 'governorate_id' => 33];
        $cities[] = ['name' => 'الشملي', 'governorate_id' => 33];

        // الحدود الشمالية (34)
        $cities[] = ['name' => 'عرعر', 'governorate_id' => 34];
        $cities[] = ['name' => 'رفحاء', 'governorate_id' => 34];
        $cities[] = ['name' => 'طريف', 'governorate_id' => 34];
        $cities[] = ['name' => 'العويقيلة', 'governorate_id' => 34];

        // جازان (35)
        $cities[] = ['name' => 'جازان', 'governorate_id' => 35];
        $cities[] = ['name' => 'صبيا', 'governorate_id' => 35];
        $cities[] = ['name' => 'أبو عريش', 'governorate_id' => 35];
        $cities[] = ['name' => 'صامطة', 'governorate_id' => 35];
        $cities[] = ['name' => 'بيش', 'governorate_id' => 35];
        $cities[] = ['name' => 'الدرب', 'governorate_id' => 35];
        $cities[] = ['name' => 'الريث', 'governorate_id' => 35];
        $cities[] = ['name' => 'العارضة', 'governorate_id' => 35];
        $cities[] = ['name' => 'فيفاء', 'governorate_id' => 35];
        $cities[] = ['name' => 'العيدابي', 'governorate_id' => 35];

        // نجران (36)
        $cities[] = ['name' => 'نجران', 'governorate_id' => 36];
        $cities[] = ['name' => 'شرورة', 'governorate_id' => 36];
        $cities[] = ['name' => 'حبونا', 'governorate_id' => 36];
        $cities[] = ['name' => 'ثار', 'governorate_id' => 36];
        $cities[] = ['name' => 'يَدْمَة', 'governorate_id' => 36];

        // الباحة (37)
        $cities[] = ['name' => 'الباحة', 'governorate_id' => 37];
        $cities[] = ['name' => 'المندق', 'governorate_id' => 37];
        $cities[] = ['name' => 'بلجرشي', 'governorate_id' => 37];
        $cities[] = ['name' => 'المخواة', 'governorate_id' => 37];
        $cities[] = ['name' => 'الأبناء', 'governorate_id' => 37];
        $cities[] = ['name' => 'قلوة', 'governorate_id' => 37];
        $cities[] = ['name' => 'العقيق', 'governorate_id' => 37];

        // الجوف (38)
        $cities[] = ['name' => 'سكاكا', 'governorate_id' => 38];
        $cities[] = ['name' => 'القريات', 'governorate_id' => 38];
        $cities[] = ['name' => 'دومة الجندل', 'governorate_id' => 38];
        $cities[] = ['name' => 'طبرجل', 'governorate_id' => 38];

        // القصيم (39)
        $cities[] = ['name' => 'بريدة', 'governorate_id' => 39];
        $cities[] = ['name' => 'عنيزة', 'governorate_id' => 39];
        $cities[] = ['name' => 'الرس', 'governorate_id' => 39];
        $cities[] = ['name' => 'المذنب', 'governorate_id' => 39];
        $cities[] = ['name' => 'البكيرية', 'governorate_id' => 39];
        $cities[] = ['name' => 'البدائع', 'governorate_id' => 39];
        $cities[] = ['name' => 'الأسياح', 'governorate_id' => 39];
        $cities[] = ['name' => 'النبهانية', 'governorate_id' => 39];
        $cities[] = ['name' => 'عيون الجواء', 'governorate_id' => 39];
        $cities[] = ['name' => 'رياض الخبراء', 'governorate_id' => 39];

        // ==================== الإمارات (40-46) ====================

        // أبو ظبي (40)
        $cities[] = ['name' => 'أبو ظبي', 'governorate_id' => 40];
        $cities[] = ['name' => 'العين', 'governorate_id' => 40];
        $cities[] = ['name' => 'مدينة زايد', 'governorate_id' => 40];
        $cities[] = ['name' => 'ليوا', 'governorate_id' => 40];
        $cities[] = ['name' => 'مصفح', 'governorate_id' => 40];

        // دبي (41)
        $cities[] = ['name' => 'دبي', 'governorate_id' => 41];
        $cities[] = ['name' => 'البرشاء', 'governorate_id' => 41];
        $cities[] = ['name' => 'مرسى دبي', 'governorate_id' => 41];
        $cities[] = ['name' => 'جميرا', 'governorate_id' => 41];
        $cities[] = ['name' => 'ديرة', 'governorate_id' => 41];

        // الشارقة (42)
        $cities[] = ['name' => 'الشارقة', 'governorate_id' => 42];
        $cities[] = ['name' => 'خورفكان', 'governorate_id' => 42];
        $cities[] = ['name' => 'كلباء', 'governorate_id' => 42];
        $cities[] = ['name' => 'دبا الحصن', 'governorate_id' => 42];
        $cities[] = ['name' => 'الذيد', 'governorate_id' => 42];

        // عجمان (43)
        $cities[] = ['name' => 'عجمان', 'governorate_id' => 43];
        $cities[] = ['name' => 'مصفوت', 'governorate_id' => 43];

        // أم القيوين (44)
        $cities[] = ['name' => 'أم القيوين', 'governorate_id' => 44];

        // رأس الخيمة (45)
        $cities[] = ['name' => 'رأس الخيمة', 'governorate_id' => 45];

        // الفجيرة (46)
        $cities[] = ['name' => 'الفجيرة', 'governorate_id' => 46];
        $cities[] = ['name' => 'دبا الفجيرة', 'governorate_id' => 46];

        // ==================== الكويت (47-52) ====================

        // العاصمة (47)
        $cities[] = ['name' => 'مدينة الكويت', 'governorate_id' => 47];
        $cities[] = ['name' => 'المرقاب', 'governorate_id' => 47];
        $cities[] = ['name' => 'القبلة', 'governorate_id' => 47];
        $cities[] = ['name' => 'الشرق', 'governorate_id' => 47];
        $cities[] = ['name' => 'دسمان', 'governorate_id' => 47];
        $cities[] = ['name' => 'الدعية', 'governorate_id' => 47];

        // حولي (48)
        $cities[] = ['name' => 'حولي', 'governorate_id' => 48];
        $cities[] = ['name' => 'السالمية', 'governorate_id' => 48];
        $cities[] = ['name' => 'الجابرية', 'governorate_id' => 48];
        $cities[] = ['name' => 'ميدان حولي', 'governorate_id' => 48];

        // الفروانية (49)
        $cities[] = ['name' => 'الفروانية', 'governorate_id' => 49];
        $cities[] = ['name' => 'الأندلس', 'governorate_id' => 49];
        $cities[] = ['name' => 'العارضية', 'governorate_id' => 49];

        // الجهراء (50)
        $cities[] = ['name' => 'الجهراء', 'governorate_id' => 50];
        $cities[] = ['name' => 'تيماء', 'governorate_id' => 50];
        $cities[] = ['name' => 'الصليبية', 'governorate_id' => 50];

        // مبارك الكبير (51)
        $cities[] = ['name' => 'مبارك الكبير', 'governorate_id' => 51];
        $cities[] = ['name' => 'صباح السالم', 'governorate_id' => 51];
        $cities[] = ['name' => 'أبو الحصانية', 'governorate_id' => 51];

        // الأحمدي (52)
        $cities[] = ['name' => 'الأحمدي', 'governorate_id' => 52];
        $cities[] = ['name' => 'الفحيحيل', 'governorate_id' => 52];
        $cities[] = ['name' => 'المسيلة', 'governorate_id' => 52];

        // ==================== قطر (53-59) ====================

        // الدوحة (53)
        $cities[] = ['name' => 'الدوحة', 'governorate_id' => 53];
        $cities[] = ['name' => 'الريان', 'governorate_id' => 54];
        $cities[] = ['name' => 'الوكرة', 'governorate_id' => 55];
        $cities[] = ['name' => 'الخور', 'governorate_id' => 56];
        $cities[] = ['name' => 'الشمال', 'governorate_id' => 57];
        $cities[] = ['name' => 'أم صلال', 'governorate_id' => 58];
        $cities[] = ['name' => 'الضعاين', 'governorate_id' => 59];

        // ==================== عمان (60-66) ====================

        // مسقط (60)
        $cities[] = ['name' => 'مسقط', 'governorate_id' => 60];
        $cities[] = ['name' => 'صلالة', 'governorate_id' => 61];
        $cities[] = ['name' => 'صحار', 'governorate_id' => 62];
        $cities[] = ['name' => 'نزوى', 'governorate_id' => 63];
        $cities[] = ['name' => 'إبراء', 'governorate_id' => 64];
        $cities[] = ['name' => 'صور', 'governorate_id' => 65];
        $cities[] = ['name' => 'البريمي', 'governorate_id' => 66];

        foreach ($cities as $city) {
            City::updateOrCreate(
                [
                    'name' => $city['name'],
                    'governorate_id' => $city['governorate_id'],
                    'company_id' => 1
                ],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Cities seeded: ' . count($cities) . ' records');
    }
}
