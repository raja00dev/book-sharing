<?php

return [
    'required' => ':attribute الزامی است.',
    'digits' => ':attribute باید شش‌رقمی باشد.',
    'regex' => 'قالب :attribute معتبر نیست.',
    'image' => 'فایل :attribute باید یک تصویر معتبر باشد.',
    'unique' => ':attribute قبلاً ثبت شده است.',
    'min' => [
        'numeric' => 'مقدار :attribute نباید کمتر از :min باشد.',
        'string' => ':attribute باید حداقل :min کاراکتر باشد.',
    ],
    'max' => [
        'array' => 'تعداد :attribute نباید بیشتر از :max باشد.',
        'file' => 'حجم :attribute نباید بیشتر از :max کیلوبایت باشد.',
        'numeric' => 'مقدار :attribute نباید بیشتر از :max باشد.',
        'string' => ':attribute نباید بیشتر از :max کاراکتر باشد.',
    ],

    'custom' => [
        'phone' => [
            'regex' => 'شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.',
        ],
        'otp' => [
            'incorrect' => 'کد واردشده صحیح نیست.',
        ],
    ],

    'attributes' => [
        'phone' => 'شماره موبایل',
        'otp' => 'کد یکبارمصرف',
        'password' => 'رمز عبور',
        'bookTitle' => 'عنوان کتاب',
        'bookDescription' => 'توضیحات کتاب',
        'bookAuthorId' => 'نویسنده',
        'bookPrice' => 'قیمت کتاب',
        'bookStock' => 'موجودی',
        'bookPublisher' => 'ناشر',
        'bookPublicationYear' => 'سال انتشار',
        'bookPages' => 'تعداد صفحه',
        'bookCoverImage' => 'تصویر جلد',
        'coverImage' => 'تصویر جلد کتاب',
        'bookCategoryIds' => 'دسته‌بندی‌ها',
        'authorName' => 'نام نویسنده',
        'authorBio' => 'زندگی‌نامه',
        'authorImage' => 'تصویر نویسنده',
        'image' => 'تصویر',
        'adminName' => 'نام مدیر',
        'adminPhone' => 'شماره موبایل مدیر',
        'adminPassword' => 'رمز عبور مدیر',
        'adminPasswordConfirmation' => 'تکرار رمز عبور',
        'bannerText' => 'متن بنر',
        'bannerImage' => 'تصویر بنر',
    ],
];
