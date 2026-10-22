<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'text' => 'با یک کتاب تازه، روزت را شروع کن',
                'image' => '/banner-reading.png',
            ],
            [
                'text' => 'کتاب خوب منتظر خواننده‌ی بعدی است',
                'image' => '/banner-sharing.png',
            ],
            [
                'text' => 'قفسه‌ها را برای کشف تازه آماده کن',
                'image' => '/banner-discovery.png',
            ],
            [
                'text' => 'داستانی تازه برای لحظه‌های آرام',
                'image' => '/banner-reading.png',
            ],
            [
                'text' => 'خواندن را با دیگران به اشتراک بگذار',
                'image' => '/banner-sharing.png',
            ],
        ];

        foreach ($banners as $banner) {
            $record = Banner::query()->firstOrCreate(
                ['text' => $banner['text']],
                [
                    'image' => $banner['image'],
                    'isBig' => true,
                    'status' => true,
                ],
            );

            if (! $record->status) {
                $record->update(['status' => true]);
            }
        }
    }
}
