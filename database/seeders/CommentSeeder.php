<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::query()->delete();

        $userIds = User::query()->pluck('id')->all();
        if ($userIds === []) {
            User::factory()->count(30)->create();
            $userIds = User::query()->pluck('id')->all();
        }

        $books = Book::query()->get();
        if ($books->isEmpty()) {
            $this->call(BookCatalogSeeder::class);
            $books = Book::query()->get();
        }

        if ($books->isEmpty() || $userIds === []) {
            throw new \RuntimeException('There are no users or books in the database to attach comments to.');
        }

        $bodyTemplates = [
            'این کتاب واقعاً جالبه.',
            'خیلی خوب و خواندنی بود.',
            'توضیحات کتاب دقیق و واضح بودند.',
            'از خرید این کتاب راضی بودم.',
            'نکات جذاب و آموزنده داشت.',
            'کتاب برایم الهام‌بخش بود.',
            'مطالعه‌اش لذت بخش بود.',
            'به نظرم ارزش خوندن داره.',
            'کیفیت محتوا عالی بود.',
            'ممنون از نویسنده برای این اثر خوب.',
            'داستان خیلی روان و جذاب بود.',
            'خیلی هم ارزش خوندن دارد.',
            'بسیار خوب و حرفه‌ای نوشته شده.',
            'از خواندنش لذت بردم و پیشنهاد می‌کنم.',
            'ایده‌ی کتاب خیلی قوی و جذاب بود.',
        ];

        $totalInserted = 0;

        foreach ($books as $book) {
            $comments = [];

            foreach (range(1, 1000) as $index) {
                $comments[] = [
                    'user_id' => $userIds[array_rand($userIds)],
                    'book_id' => $book->id,
                    'body' => fake()->randomElement($bodyTemplates) . ' ' . fake()->sentence(rand(5, 12)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Comment::query()->insert($comments);
            $totalInserted += count($comments);
        }

        $this->command->info('Inserted ' . $totalInserted . ' comments for ' . $books->count() . ' books.');
    }
}
