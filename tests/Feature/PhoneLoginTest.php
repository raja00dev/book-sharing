<?php

use App\Livewire\Auth\PhoneLogin;
use App\Livewire\Home;
use App\Livewire\ProfileEdit;
use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use App\Support\PersianDate;
use Database\Seeders\BookCatalogSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('the login page is shown to guests', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('wire:name="auth.phone-login"', false);

    Livewire::test(PhoneLogin::class)
        ->assertSee('شماره موبایل')
        ->set('phone', '09123456789')
        ->call('continueToCode')
        ->assertSee('123456');
});

test('dates are formatted with the Persian calendar', function () {
    $date = new DateTimeImmutable('2024-03-20 12:34:00', new DateTimeZone('UTC'));

    expect(PersianDate::format($date))->toBe('۱۴۰۳/۰۱/۰۱')
        ->and(PersianDate::format($date, 'yyyy/MM/dd HH:mm'))->toBe('۱۴۰۳/۰۱/۰۱ ۱۶:۰۴');
});

test('guests are redirected from the homepage to login', function () {
    $this->get('/')->assertRedirect(route('login'));
});

test('an authenticated user sees the home page and an empty books state', function () {
    $user = User::factory()->create([
        'name' => 'کتاب‌خوان',
        'phone' => '09123456780',
    ]);

    $this->actingAs($user)
        ->get('/')
        ->assertOk()
        ->assertSee('امروز دلت چه قصه‌ای می‌خواهد؟')
        ->assertSee('هنوز کتابی در قفسه نیست.');
});

test('the homepage lists recent active books and their authors', function () {
    $author = Author::create(['name' => 'نویسنده‌ی نمونه']);
    Book::create([
        'title' => 'کتاب تازه',
        'author_id' => $author->id,
        'price' => 100000,
        'is_active' => true,
    ]);

    $this->actingAs(User::factory()->create(['phone' => '09123456781']))
        ->get('/')
        ->assertOk()
        ->assertSee('کتاب تازه')
        ->assertSee('نویسنده‌ی نمونه');
});

test('the homepage displays seeded banners and book categories', function () {
    $this->seed(BookCatalogSeeder::class);

    $this->actingAs(User::factory()->create(['phone' => '09123456782']))
        ->get('/')
        ->assertOk()
        ->assertSee('هر کتاب، پنجره‌ای به یک جهان تازه')
        ->assertSee('داستان و رمان')
        ->assertSee('ماجراجویی')
        ->assertSee('/banner-reading.png', false)
        ->assertSee('/banner-discovery.png', false)
        ->assertSee('/banner-sharing.png', false)
        ->assertSee('data-carousel', false);
});

test('phone validation messages are translated from the Persian language file', function () {
    Livewire::test(PhoneLogin::class)
        ->call('continueToCode')
        ->assertHasErrors(['phone' => 'required'])
        ->assertSee('شماره موبایل الزامی است.');

    Livewire::test(PhoneLogin::class)
        ->set('phone', '123')
        ->call('continueToCode')
        ->assertHasErrors(['phone' => 'regex'])
        ->assertSee('شماره موبایل باید ۱۱ رقم و با ۰۹ شروع شود.');

    Livewire::test(PhoneLogin::class)
        ->set('phone', '09123456789')
        ->call('continueToCode')
        ->call('login')
        ->assertHasErrors(['otp' => 'required'])
        ->assertSee('کد یکبارمصرف الزامی است.');

    Livewire::test(PhoneLogin::class)
        ->set('phone', '09123456789')
        ->call('continueToCode')
        ->set('otp', '123')
        ->call('login')
        ->assertHasErrors(['otp' => 'digits'])
        ->assertSee('کد یکبارمصرف باید شش‌رقمی باشد.');
});

test('a guest can sign in with a valid phone and the default code', function () {
    Livewire::test(PhoneLogin::class)
        ->set('phone', '09123456789')
        ->call('continueToCode')
        ->assertSet('step', 'code')
        ->set('otp', '123456')
        ->call('login')
        ->assertRedirect(route('home'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'phone' => '09123456789',
        'name' => 'کاربر',
    ]);

    expect(User::where('phone', '09123456789')->first()->phone_verified_at)->not->toBeNull();
});

test('a guest cannot continue with Persian numerals in their phone number', function () {
    Livewire::test(PhoneLogin::class)
        ->set('phone', '۰۹۱۲۳۴۵۶۷۸۹')
        ->call('continueToCode')
        ->assertHasErrors('phone');

    $this->assertGuest();
});

test('an incorrect code does not sign in or create a user', function () {
    Livewire::test(PhoneLogin::class)
        ->set('phone', '09123456789')
        ->call('continueToCode')
        ->set('otp', '000000')
        ->call('login')
        ->assertHasErrors('otp')
        ->assertSee('کد واردشده صحیح نیست.');

    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['phone' => '09123456789']);
});

test('a regular user can update their profile name and avatar', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'phone' => '09123456784',
        'name' => 'کاربر قدیمی',
    ]);

    Livewire::actingAs($user)
        ->test(ProfileEdit::class)
        ->set('name', 'کاربر جدید')
        ->set('avatar', UploadedFile::fake()->image('avatar.jpg'))
        ->call('save')
        ->assertRedirect(route('profile'));

    $user->refresh();
    expect($user->name)->toBe('کاربر جدید')
        ->and($user->avatar)->not->toBeNull();
});

test('a regular user can change their password without entering the current password', function () {
    $user = User::factory()->create([
        'phone' => '09123456787',
        'password' => 'old-password',
    ]);

    Livewire::actingAs($user)
        ->test(ProfileEdit::class)
        ->set('password', 'new-password')
        ->set('passwordConfirmation', 'new-password')
        ->call('save')
        ->assertRedirect(route('profile'));

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

test('a user without a password can set their first password', function () {
    $user = User::factory()->create([
        'phone' => '09123456789',
        'password' => null,
    ]);

    Livewire::actingAs($user)
        ->test(ProfileEdit::class)
        ->set('password', 'first-password')
        ->set('passwordConfirmation', 'first-password')
        ->call('save')
        ->assertRedirect(route('profile'));

    expect(Hash::check('first-password', $user->fresh()->password))->toBeTrue();
});

test('a regular user must confirm their new password', function () {
    $user = User::factory()->create([
        'phone' => '09123456788',
        'password' => 'old-password',
    ]);

    Livewire::actingAs($user)
        ->test(ProfileEdit::class)
        ->set('password', 'new-password')
        ->set('passwordConfirmation', 'different-password')
        ->call('save')
        ->assertHasErrors('password');

    expect(Hash::check('old-password', $user->fresh()->password))->toBeTrue();
});

test('the profile page shows account options before opening the edit form', function () {
    $user = User::factory()->create([
        'phone' => '09123456786',
        'name' => 'کتاب‌خوان',
    ]);

    $this->actingAs($user)
        ->get(route('profile'))
        ->assertOk()
        ->assertSee('پروفایل شما')
        ->assertSee('ویرایش نام و عکس پروفایل')
        ->assertSee(route('profile.edit'), false)
        ->assertSee(route('orders.mine'), false)
        ->assertDontSee('id="name"', false)
        ->assertDontSee('id="avatar"', false);

    $this->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('id="name"', false);
});

test('a user can open a book detail page and view comments', function () {
    $user = User::factory()->create(['phone' => '09123456785', 'name' => 'خواننده']);
    $author = Author::create(['name' => 'نویسنده‌ی خبر']);
    $book = Book::create([
        'title' => 'کتاب داستانی',
        'author_id' => $author->id,
        'price' => 220000,
        'stock' => 8,
        'cover_image' => 'books/detail-cover.jpg',
        'is_active' => true,
    ]);
    $book->comments()->create([
        'user_id' => $user->id,
        'body' => 'خیلی خوب بود.',
    ]);
    $book->comments()->create([
        'user_id' => $user->id,
        'body' => 'این نظر مسدود است.',
        'is_blocked' => true,
    ]);

    $this->actingAs($user)
        ->get(route('books.show', $book))
        ->assertOk()
        ->assertSee('کتاب داستانی')
        ->assertSee('220,000')
        ->assertSee('خیلی خوب بود.')
        ->assertDontSee('این نظر مسدود است.');
});

test('an authenticated user can log out from the homepage', function () {
    $user = User::factory()->create([
        'phone' => '09123456789',
    ]);

    $this->actingAs($user);

    Livewire::test(Home::class)
        ->call('logout')
        ->assertRedirect(route('login'));

    $this->assertGuest();
});
