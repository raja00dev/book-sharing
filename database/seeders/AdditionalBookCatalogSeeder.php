<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Categories;
use Illuminate\Database\Seeder;

class AdditionalBookCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalog = [
            ['category' => 'ادبیات کلاسیک', 'author' => 'آرمان صادقی'],
            ['category' => 'علم و فناوری', 'author' => 'مهتاب نیک‌روش'],
            ['category' => 'تاریخ و تمدن', 'author' => 'کاوه مهران'],
            ['category' => 'روان‌شناسی', 'author' => 'رعنا بهرامی'],
            ['category' => 'سفر و جغرافیا', 'author' => 'سامان فرهمند'],
            ['category' => 'شعر معاصر', 'author' => 'لیلا آذر'],
            ['category' => 'کودک و نوجوان', 'author' => 'نگار آشتیانی'],
            ['category' => 'فلسفه و اندیشه', 'author' => 'مانی راد'],
            ['category' => 'هنر و طراحی', 'author' => 'آوا کیانی'],
            ['category' => 'آشپزی', 'author' => 'ترانه کاویانی'],
        ];

        $titleTemplates = [
            'آشنایی با %s',
            'راهنمای کاربردی %s',
            'نگاهی تازه به %s',
            'از مبانی تا تجربه در %s',
            'روایت‌هایی درباره‌ی %s',
            'در جست‌وجوی %s',
            'قصه‌ها و اندیشه‌های %s',
            'جهان پنهان %s',
            'پرسش‌های مهم درباره‌ی %s',
            'افق‌های تازه‌ی %s',
            'یادداشت‌هایی پیرامون %s',
            'مسیرهای کشف %s',
            'فصل‌هایی از %s',
            'زندگی روزمره و %s',
            'چهره‌های ماندگار %s',
            'گزیده‌ای برای دوستداران %s',
            'رازی در دل %s',
            'سفری کوتاه به دنیای %s',
            'درس‌هایی از %s',
            'آینده‌ی %s',
        ];

        $publishers = ['نشر سپیدار', 'کتاب آبان', 'نشر جوانه', 'نشر راه روشن', 'نشر برگ'];

        foreach ($catalog as $categoryIndex => $entry) {
            $category = Categories::query()->firstOrCreate(['name' => $entry['category']]);
            $author = Author::query()->firstOrCreate(
                ['name' => $entry['author']],
                ['bio' => 'نویسنده و پژوهشگر حوزه‌ی '.$entry['category'].'.'],
            );

            foreach ($titleTemplates as $bookIndex => $titleTemplate) {
                $title = sprintf($titleTemplate, $entry['category']);
                $book = Book::query()->firstOrCreate(
                    [
                        'title' => $title,
                        'author_id' => $author->id,
                    ],
                    [
                        'description' => 'کتابی برای آشنایی بیشتر با '.$entry['category'].'؛ مناسب برای مطالعه و گسترش دیدگاه.',
                        'price' => 65000 + ($categoryIndex * 11000) + ($bookIndex * 3500),
                        'stock' => 2 + ($bookIndex % 9),
                        'publisher' => $publishers[$categoryIndex % count($publishers)],
                        'publication_year' => 1390 + ($bookIndex % 15),
                        'pages' => 120 + (($bookIndex % 18) * 12),
                        'is_active' => true,
                    ],
                );

                $book->categories()->syncWithoutDetaching([$category->id]);
            }
        }
    }
}
