<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;

class ServiceCategorySeeder extends Seeder
{
    public function run()
    {
      $categories = [
    'تصميم' => 'خدمات تصميم احترافية تشمل الشعارات والمطبوعات.',
    'برمجة' => 'خدمات تطوير مواقع وتطبيقات باستخدام أحدث التقنيات.',
    'تسويق' => 'خدمات تسويق رقمي وإعلانات مدفوعة.',
    'كتابة' => 'خدمات كتابة محتوى وتحرير لغوي.',
];

foreach ($categories as $name => $desc) {
    ServiceCategory::create([
        'category_name' => $name,
        'category_desc' => $desc,
    ]);
}


    }
}

