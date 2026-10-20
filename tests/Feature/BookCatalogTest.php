<?php

use App\Livewire\BookCatalog;
use App\Models\Author;
use App\Models\Book;
use App\Models\Categories;
use App\Models\User;
use Livewire\Livewire;

test('an authenticated user can search books by title, author, and category', function () {
    $reader = User::factory()->create(['phone' => '09120000011']);
    $author = Author::create(['name' => 'نویسنده‌ی آبی']);
    $otherAuthor = Author::create(['name' => 'نویسنده‌ی سبز']);
    $category = Categories::create(['name' => 'علمی‌تخیلی']);

    $blueBook = Book::create([
        'title' => 'کتاب آبی',
        'author_id' => $author->id,
        'price' => 120000,
        'stock' => 2,
        'is_active' => true,
    ]);
    $blueBook->categories()->attach($category);

    Book::create([
        'title' => 'کتاب سبز',
        'author_id' => $otherAuthor->id,
        'price' => 80000,
        'stock' => 1,
        'is_active' => true,
    ]);
    Book::create([
        'title' => 'کتاب پنهان',
        'author_id' => $author->id,
        'price' => 70000,
        'stock' => 1,
        'is_active' => false,
    ]);

    $this->actingAs($reader)
        ->get(route('books.catalog'))
        ->assertOk()
        ->assertSee('کتاب آبی')
        ->assertSee('کتاب سبز')
        ->assertDontSee('مرتب‌سازی')
        ->assertDontSee(route('orders.mine'), false)
        ->assertDontSee('کتاب پنهان');

    Livewire::actingAs($reader)
        ->test(BookCatalog::class)
        ->set('search', 'کتاب آبی')
        ->assertSee('کتاب آبی')
        ->assertDontSee('کتاب سبز')
        ->set('search', 'نویسنده‌ی سبز')
        ->assertSee('کتاب سبز')
        ->assertDontSee('کتاب آبی')
        ->set('search', '')
        ->set('categoryId', (string) $category->id)
        ->assertSee('کتاب آبی')
        ->assertDontSee('کتاب سبز');
});

test('clicking a homepage category opens the catalog filtered to that category', function () {
    $reader = User::factory()->create(['phone' => '09120000013']);
    $author = Author::create(['name' => 'نویسنده‌ی دسته']);
    $category = Categories::create(['name' => 'ماجراجویی']);
    $matchingBook = Book::create([
        'title' => 'سفر دوردست',
        'author_id' => $author->id,
        'price' => 75000,
        'stock' => 2,
        'is_active' => true,
    ]);
    $matchingBook->categories()->attach($category);
    Book::create([
        'title' => 'داستان دیگر',
        'author_id' => $author->id,
        'price' => 85000,
        'stock' => 1,
        'is_active' => true,
    ]);

    $this->actingAs($reader)
        ->get(route('home'))
        ->assertOk()
        ->assertSee(route('books.catalog', ['category' => $category->id]), false);

    $this->get(route('books.catalog', ['category' => $category->id]))
        ->assertOk()
        ->assertSee('سفر دوردست')
        ->assertDontSee('داستان دیگر');
});

test('a guest is redirected before opening book search', function () {
    $this->get(route('books.catalog'))->assertRedirect(route('login'));
});

test('the order history link is available from the profile, not the home header', function () {
    $reader = User::factory()->create(['phone' => '09120000012']);

    $this->actingAs($reader)
        ->get(route('profile'))
        ->assertOk()
        ->assertSee('سفارش‌ها و پرداخت‌های من')
        ->assertSee(route('orders.mine'), false);

    $this->get(route('home'))->assertOk()->assertDontSee(route('orders.mine'), false);
});
