<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Banner;
use App\Models\Book;
use App\Models\Categories;
use Illuminate\Database\Seeder;

class BookCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalog = [
            [
                'author' => 'نیلوفر راد',
                'bio' => 'نویسنده‌ی داستان‌هایی درباره‌ی خانه، خاطره و شروع دوباره.',
                'books' => [
                    [
                        'title' => 'نقشه‌ی کوچک باران',
                        'description' => 'داستانی آرام درباره‌ی پیدا کردن راه خانه در روزهای بارانی.',
                        'price' => 185000,
                        'stock' => 3,
                        'publisher' => 'نشر سپیدار',
                        'publication_year' => 1402,
                        'pages' => 224,
                    ],
                    [
                        'title' => 'اتاقی رو به باغ',
                        'description' => 'روایتی از دوستی‌های تازه و باغی که رازهای قدیمی را نگه می‌دارد.',
                        'price' => 210000,
                        'stock' => 2,
                        'publisher' => 'نشر سپیدار',
                        'publication_year' => 1403,
                        'pages' => 248,
                    ],
                ],
            ],
            [
                'author' => 'بهنام فرهمند',
                'bio' => 'داستان‌نویس و علاقه‌مند به قصه‌های محله‌های قدیمی.',
                'books' => [
                    [
                        'title' => 'چراغ‌های کوچه‌ی دور',
                        'description' => 'چند همسایه در جست‌وجوی معنای چراغی که هر شب روشن می‌شود.',
                        'price' => 198000,
                        'stock' => 4,
                        'publisher' => 'کتاب آبان',
                        'publication_year' => 1401,
                        'pages' => 208,
                    ],
                ],
            ],
            [
                'author' => 'سارا آذرخش',
                'bio' => 'نویسنده‌ی قصه‌های ماجراجویانه برای خوانندگان کنجکاو.',
                'books' => [
                    [
                        'title' => 'راهی که به باغ رسید',
                        'description' => 'سفری کوتاه که به کشف یک باغ فراموش‌شده ختم می‌شود.',
                        'price' => 220000,
                        'stock' => 1,
                        'publisher' => 'نشر جوانه',
                        'publication_year' => 1404,
                        'pages' => 256,
                    ],
                ],
            ],
        ];

        foreach ($catalog as $entry) {
            $author = Author::firstOrCreate(
                ['name' => $entry['author']],
                ['bio' => $entry['bio']]
            );

            foreach ($entry['books'] as $book) {
                Book::firstOrCreate(
                    [
                        'title' => $book['title'],
                        'author_id' => $author->id,
                    ],
                    [
                        ...$book,
                        'is_active' => true,
                    ]
                );
            }
        }

        $categoryBooks = [
            'داستان و رمان' => [
                'نقشه‌ی کوچک باران',
                'اتاقی رو به باغ',
                'چراغ‌های کوچه‌ی دور',
                'راهی که به باغ رسید',
            ],
            'ماجراجویی' => [
                'راهی که به باغ رسید',
                'چراغ‌های کوچه‌ی دور',
            ],
            'خانه و خاطره' => [
                'نقشه‌ی کوچک باران',
                'اتاقی رو به باغ',
            ],
            'حال خوب' => [
                'اتاقی رو به باغ',
                'راهی که به باغ رسید',
            ],
        ];

        foreach ($categoryBooks as $name => $titles) {
            $category = Categories::firstOrCreate(['name' => $name]);
            $books = Book::query()->whereIn('title', $titles)->get();

            foreach ($books as $book) {
                $book->categories()->syncWithoutDetaching([$category->id]);
            }
        }

        $banners = [
            [
                'text' => 'هر کتاب، پنجره‌ای به یک جهان تازه',
                'image' => '/banner-reading.png',
                'isBig' => true,
            ],
            [
                'text' => 'داستان بعدی‌ات را در قفسه‌ها پیدا کن',
                'image' => '/banner-discovery.png',
                'isBig' => true,
            ],
            [
                'text' => 'کتاب‌ها را به گردش بینداز',
                'image' => '/banner-sharing.png',
                'isBig' => true,
            ],
        ];

        foreach ($banners as $banner) {
            $record = Banner::firstOrCreate(
                ['text' => $banner['text']],
                [...$banner, 'status' => true]
            );

            if ($record->image !== $banner['image']) {
                $record->update(['image' => $banner['image']]);
            }
        }
    }
}
