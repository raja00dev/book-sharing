<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Categories;
use Illuminate\Database\Seeder;

class RandomBookCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'داستان کوتاه',
            'دانش و فناوری',
            'زندگی‌نامه',
            'مدیریت',
            'طبیعت و محیط زیست',
            'هنرهای تجسمی',
            'شعر کلاسیک',
            'سلامت و تندرستی',
            'کسب‌وکار',
            'ادبیات جهان',
        ];

        $authors = [
            'آرزو نادری',
            'پارسا کیانی',
            'مهسا رستگار',
            'سامان فرهمند',
            'نرگس سهرابی',
            'بردیا توکلی',
            'لیلا بهمنی',
            'مانی شریفی',
            'روژان کریمی',
            'یاسین امیری',
        ];

        $bookTitles = [
            'خانه‌ای میان ابرها',
            'فردای روشن',
            'قصه‌های کنار پنجره',
            'مسیر آبی',
            'نگاهی به زندگی',
            'راز باغ قدیمی',
            'یادداشت‌های یک مسافر',
            'فصل تازه',
            'دنیای رنگ‌ها',
            'چراغی در دوردست',
        ];

        $publishers = ['نشر سپیدار', 'کتاب آبان', 'نشر جوانه', 'نشر راه روشن', 'نشر برگ'];
        $createdCategories = [];

        foreach ($categories as $categoryName) {
            $category = Categories::query()->firstOrCreate(['name' => $categoryName]);
            $createdCategories[] = $category;
        }

        foreach ($bookTitles as $index => $title) {
            $author = Author::query()->firstOrCreate(
                ['name' => $authors[$index]],
                ['bio' => 'نویسنده و پژوهشگر علاقه‌مند به کتاب و ادبیات.'],
            );

            $book = Book::query()->firstOrCreate(
                ['title' => $title, 'author_id' => $author->id],
                [
                    'description' => 'کتابی خواندنی برای دوستداران مطالعه و کشف موضوع‌های تازه.',
                    'price' => fake()->numberBetween(70000, 350000),
                    'stock' => fake()->numberBetween(1, 15),
                    'publisher' => fake()->randomElement($publishers),
                    'publication_year' => fake()->numberBetween(1395, 1404),
                    'pages' => fake()->numberBetween(120, 450),
                    'is_active' => true,
                ],
            );

            $book->categories()->syncWithoutDetaching(
                fake()->randomElements($createdCategories, fake()->numberBetween(1, 3)),
            );
        }
    }
}
