<?php

use App\Livewire\Admin\Admins\Create as CreateAdmin;
use App\Livewire\Admin\Authors\Create as CreateAuthor;
use App\Livewire\Admin\Banners\Create as CreateBanner;
use App\Livewire\Admin\Books\Create as CreateBook;
use App\Livewire\Admin\Books\Index as AdminBooksIndex;
use App\Livewire\Admin\Categories\Create as CreateCategory;
use App\Livewire\Admin\Comments\Create as CreateComment;
use App\Livewire\Admin\Comments\Edit as EditComment;
use App\Livewire\Admin\Comments\Index as AdminCommentsIndex;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Overview;
use App\Livewire\Admin\Users\Edit as EditUser;
use App\Livewire\Auth\PhoneLogin as PhoneLoginComponent;
use App\Models\Author;
use App\Models\Banner;
use App\Models\Book;
use App\Models\Categories;
use App\Models\Comment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('the admin login page offers password and otp login without exposing the test code', function () {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('رمز عبور')
        ->assertSee('کد یکبارمصرف')
        ->assertDontSee('کد آزمایشی:')
        ->assertSee('پنل مدیریت');
});

test('an admin cannot login without a password', function () {
    User::factory()->create([
        'phone' => '09111111110',
        'is_admin' => true,
    ]);

    Livewire::test(Login::class)
        ->set('phone', '09111111110')
        ->call('authenticate')
        ->assertHasErrors('password');

    $this->assertGuest();
});

test('an admin can sign in with phone and password', function () {
    $admin = User::factory()->create([
        'phone' => '09111111111',
        'is_admin' => true,
        'password' => 'correct-password',
    ]);

    Livewire::test(Login::class)
        ->set('phone', '09111111111')
        ->set('password', 'correct-password')
        ->call('authenticate')
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($admin);
});

test('an admin can sign in with phone and the site otp flow', function () {
    $admin = User::factory()->create([
        'phone' => '09111111112',
        'is_admin' => true,
    ]);

    Livewire::test(Login::class)
        ->set('mode', 'otp')
        ->set('phone', '09111111112')
        ->call('continueToOtp')
        ->assertSet('otpReady', true)
        ->set('otp', PhoneLoginComponent::DEFAULT_OTP)
        ->call('authenticate')
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($admin);
    expect($admin->fresh()->phone_verified_at)->not->toBeNull();
});

test('a regular user cannot use the admin password login', function () {
    User::factory()->create([
        'phone' => '09111111113',
        'is_admin' => false,
        'password' => 'correct-password',
    ]);

    Livewire::test(Login::class)
        ->set('phone', '09111111113')
        ->set('password', 'correct-password')
        ->call('authenticate')
        ->assertHasErrors('credentials')
        ->assertSee('این حساب دسترسی مدیریت ندارد');

    $this->assertGuest();
});

test('the admin dashboard redirects guests and rejects regular users', function () {
    $this->get('/admin')->assertRedirect(route('admin.login'));

    $user = User::factory()->create([
        'phone' => '09111111114',
        'is_admin' => false,
    ]);

    $this->actingAs($user)->get('/admin')->assertForbidden();
});

test('the admin dashboard shows a dedicated lists section for main resources', function () {
    $admin = User::factory()->create([
        'phone' => '09111111115',
        'is_admin' => true,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('لیست‌ها')
        ->assertSee(route('admin.books.index'), false)
        ->assertSee(route('admin.authors.index'), false)
        ->assertSee(route('admin.categories.index'), false)
        ->assertSee(route('admin.comments.index'), false)
        ->assertSee(route('admin.orders.index'), false);
});

test('an admin can delete a book from the resource list and open the edit page', function () {
    $admin = User::factory()->create([
        'phone' => '09111111119',
        'is_admin' => true,
    ]);
    $author = Author::create(['name' => 'نویسنده‌ی قابل حذف']);
    $book = Book::create([
        'title' => 'کتاب قابل حذف',
        'author_id' => $author->id,
        'price' => 100000,
        'stock' => 3,
        'cover_image' => 'books/test.jpg',
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(AdminBooksIndex::class)
        ->call('delete', $book->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('books', ['id' => $book->id]);
    $this->actingAs($admin)
        ->get(route('admin.books.edit', ['id' => $book->id]))
        ->assertNotFound();
});

test('an admin can view the dashboard and log out', function () {
    $admin = User::factory()->create([
        'phone' => '09111111115',
        'is_admin' => true,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee(config('app.name'))
        ->assertSee('نمای کلی')
        ->assertSee(route('admin.books.create'), false)
        ->assertSee('viewBox="0 0 24 24"', false)
        ->assertSee('منوی مدیریت')
        ->assertSee('افزودن کتاب')
        ->assertSee('افزودن نویسنده')
        ->assertSee('افزودن بنر')
        ->assertSee('افزودن ادمین');

    Livewire::actingAs($admin)
        ->test(Overview::class)
        ->call('toggleSidebar')
        ->assertSet('sidebarOpen', true)
        ->call('closeSidebar')
        ->assertSet('sidebarOpen', false);

    Livewire::actingAs($admin)
        ->test(Overview::class)
        ->call('logout')
        ->assertRedirect(route('admin.login'));

    $this->assertGuest();
});

test('an admin can create an author from the dashboard', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'phone' => '09111111117',
        'is_admin' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(CreateAuthor::class)
        ->set('name', 'نویسنده‌ی تازه')
        ->set('bio', 'زندگی‌نامه‌ی نمونه')
        ->set('image', UploadedFile::fake()->image('author.jpg'))
        ->call('save')
        ->assertSee('نویسنده با موفقیت اضافه شد.');

    $this->assertDatabaseHas('authors', ['name' => 'نویسنده‌ی تازه']);
    Storage::disk('public')->assertExists(Author::where('name', 'نویسنده‌ی تازه')->value('image'));
});

test('an admin can create a category from the admin panel', function () {
    $admin = User::factory()->create([
        'phone' => '09111111124',
        'is_admin' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(CreateCategory::class)
        ->set('name', 'داستان کوتاه')
        ->call('save')
        ->assertSee('دسته‌بندی با موفقیت اضافه شد.');

    $this->assertDatabaseHas('categories', ['name' => 'داستان کوتاه']);

    $this->actingAs($admin)
        ->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('افزودن دسته‌بندی')
        ->assertSee('داستان کوتاه');
});

test('an admin can add, edit, block, unblock, and delete comments', function () {
    $admin = User::factory()->create([
        'phone' => '09111111125',
        'is_admin' => true,
    ]);
    $user = User::factory()->create([
        'phone' => '09111111126',
        'name' => 'خواننده‌ی نظر',
    ]);
    $author = Author::create(['name' => 'نویسنده‌ی نظرات']);
    $book = Book::create([
        'title' => 'کتاب برای نظرها',
        'author_id' => $author->id,
        'price' => 90000,
        'stock' => 1,
        'is_active' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(CreateComment::class)
        ->set('bookId', (string) $book->id)
        ->set('userId', (string) $user->id)
        ->set('body', 'نظر اولیه برای کتاب')
        ->call('save')
        ->assertRedirect(route('admin.comments.index'));

    $comment = Comment::query()->where('body', 'نظر اولیه برای کتاب')->firstOrFail();

    $this->actingAs($admin)
        ->get(route('admin.comments.index'))
        ->assertOk()
        ->assertSee('نظر اولیه برای کتاب');

    $this->get(route('admin.comments.create'))->assertOk()->assertSee('ثبت نظر');
    $this->get(route('admin.comments.edit', ['id' => $comment->id]))->assertOk();

    Livewire::actingAs($admin)
        ->test(EditComment::class, ['id' => $comment->id])
        ->set('body', 'متن ویرایش‌شده‌ی نظر')
        ->set('isBlocked', true)
        ->call('save')
        ->assertRedirect(route('admin.comments.index'));

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'body' => 'متن ویرایش‌شده‌ی نظر',
        'is_blocked' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(AdminCommentsIndex::class)
        ->set('search', 'متن ویرایش‌شده')
        ->assertSee('مسدود')
        ->call('toggleBlocked', $comment->id);

    $this->assertDatabaseHas('comments', ['id' => $comment->id, 'is_blocked' => false]);

    Livewire::actingAs($admin)
        ->test(AdminCommentsIndex::class)
        ->call('delete', $comment->id);

    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});

test('an admin can create a book and attach selected categories', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'phone' => '09111111118',
        'is_admin' => true,
    ]);
    $author = Author::create(['name' => 'نویسنده‌ی کتاب جدید']);
    $category = Categories::create(['name' => 'تاریخی']);

    Livewire::actingAs($admin)
        ->test(CreateBook::class)
        ->set('title', 'کتاب تازه‌ی ادمین')
        ->set('authorId', (string) $author->id)
        ->set('price', '150000')
        ->set('stock', '2')
        ->set('categoryIds', [(string) $category->id])
        ->set('coverImage', UploadedFile::fake()->image('cover.jpg'))
        ->call('save')
        ->assertSee('کتاب با موفقیت اضافه شد.');

    $book = Book::where('title', 'کتاب تازه‌ی ادمین')->firstOrFail();

    expect($book->categories)->toHaveCount(1)
        ->and($book->categories->first()->is($category))->toBeTrue();
    Storage::disk('public')->assertExists($book->cover_image);
});

test('an admin book cover upload rejects non-image files', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'phone' => '09111111122',
        'is_admin' => true,
    ]);
    $author = Author::create(['name' => 'نویسنده‌ی جلد نامعتبر']);

    Livewire::actingAs($admin)
        ->test(CreateBook::class)
        ->set('title', 'کتاب با فایل نامعتبر')
        ->set('authorId', (string) $author->id)
        ->set('price', '100000')
        ->set('stock', '1')
        ->set('coverImage', UploadedFile::fake()->create('not-an-image.txt', 2, 'text/plain'))
        ->call('save')
        ->assertHasErrors(['coverImage' => 'image'])
        ->assertSee('فایل تصویر جلد کتاب باید یک تصویر معتبر باشد.');

    $this->assertDatabaseMissing('books', ['title' => 'کتاب با فایل نامعتبر']);
    Storage::disk('public')->assertDirectoryEmpty('books');
});

test('an admin can create another admin with a hashed password', function () {
    $admin = User::factory()->create([
        'phone' => '09111111119',
        'is_admin' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(CreateAdmin::class)
        ->set('name', 'مدیر جدید')
        ->set('phone', '09123456789')
        ->set('password', 'strong-pass-123')
        ->set('passwordConfirmation', 'strong-pass-123')
        ->call('save')
        ->assertSee('ادمین جدید با موفقیت اضافه شد.');

    $newAdmin = User::where('phone', '09123456789')->firstOrFail();

    expect($newAdmin->is_admin)->toBeTrue()
        ->and($newAdmin->password)->not->toBe('strong-pass-123')
        ->and(Hash::check('strong-pass-123', $newAdmin->password))->toBeTrue();
});

test('an admin can set a user password from the user edit form', function () {
    $admin = User::factory()->create([
        'phone' => '09111111124',
        'is_admin' => true,
    ]);
    $user = User::factory()->create([
        'phone' => '09111111125',
        'is_admin' => false,
    ]);

    Livewire::actingAs($admin)
        ->test(EditUser::class, ['id' => $user->id])
        ->set('password', 'new-user-pass')
        ->set('passwordConfirmation', 'new-user-pass')
        ->call('save')
        ->assertRedirect(route('admin.users.index'));

    expect(Hash::check('new-user-pass', $user->fresh()->password))->toBeTrue();
});

test('an admin can create a banner', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'phone' => '09111111120',
        'is_admin' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(CreateBanner::class)
        ->set('text', 'بنر تازه')
        ->set('image', UploadedFile::fake()->image('banner.jpg'))
        ->set('isBig', true)
        ->call('save')
        ->assertSee('بنر با موفقیت اضافه شد.');

    $this->assertDatabaseHas('banners', [
        'text' => 'بنر تازه',
        'isBig' => true,
        'status' => true,
    ]);

    Storage::disk('public')->assertExists(Banner::where('text', 'بنر تازه')->value('image'));
});

test('each admin content action has its own route and page', function () {
    $admin = User::factory()->create([
        'phone' => '09111111121',
        'is_admin' => true,
    ]);

    $this->actingAs($admin);

    $this->get(route('admin.books.create'))
        ->assertOk()
        ->assertSee('id="book-title"', false)
        ->assertSee('افزودن کتاب')
        ->assertSee('ثبت کتاب')
        ->assertDontSee('بازگشت به نمای کلی')
        ->assertDontSee('خلاصه‌ی '.config('app.name'));

    $this->get(route('admin.authors.create'))
        ->assertOk()
        ->assertSee('id="author-name"', false)
        ->assertSee('افزودن نویسنده')
        ->assertSee('نام نویسنده')
        ->assertDontSee('ثبت کتاب');

    $this->get(route('admin.categories.create'))
        ->assertOk()
        ->assertSee('افزودن دسته‌بندی')
        ->assertSee('id="category-name"', false);

    $this->get(route('admin.comments.index'))
        ->assertOk()
        ->assertSee('نظرات کاربران')
        ->assertSee('افزودن نظر');

    $this->get(route('admin.banners.create'))
        ->assertOk()
        ->assertSee('افزودن بنر')
        ->assertSee('id="banner-image"', false)
        ->assertSee('type="file"', false);

    $this->get(route('admin.admins.create'))
        ->assertOk()
        ->assertSee('افزودن ادمین')
        ->assertSee('تکرار رمز عبور');
});

test('admin resource list pages show their records and support search', function () {
    $admin = User::factory()->create([
        'phone' => '09111111123',
        'is_admin' => true,
    ]);
    $author = Author::create(['name' => 'نویسنده در فهرست']);
    $category = Categories::create(['name' => 'علمی']);
    $book = Book::create([
        'title' => 'کتاب قابل جست‌وجو',
        'author_id' => $author->id,
        'price' => 120000,
        'stock' => 2,
        'is_active' => true,
    ]);
    $book->categories()->attach($category);
    $banner = Banner::create(['text' => 'بنر فهرست', 'image' => '/banner-reading.png']);
    $order = Order::create([
        'user_id' => $admin->id,
        'total_price' => 120000,
        'status' => 'pending',
        'recipient_name' => 'گیرنده‌ی فهرست',
        'phone' => '09111111123',
        'shipping_address' => 'تهران',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.books.index'))
        ->assertOk()
        ->assertSee('کتاب قابل جست‌وجو')
        ->assertSee('نویسنده در فهرست');

    Livewire::actingAs($admin)
        ->test(AdminBooksIndex::class)
        ->set('search', 'قابل جست‌وجو')
        ->assertSee('کتاب قابل جست‌وجو')
        ->assertDontSee('کتاب دیگری');

    $this->get(route('admin.authors.index'))->assertOk()->assertSee('نویسنده در فهرست');
    $this->get(route('admin.categories.index'))->assertOk()->assertSee('علمی');
    $this->get(route('admin.banners.index'))->assertOk()->assertSee('بنر فهرست');
    $this->get(route('admin.users.index'))->assertOk()->assertSee('09111111123');
    $this->get(route('admin.admins.index'))->assertOk()->assertSee('09111111123');
    $this->get(route('admin.orders.index'))
        ->assertOk()
        ->assertSee('فهرست سفارش‌ها')
        ->assertSee('گیرنده‌ی فهرست')
        ->assertSee('در انتظار پرداخت');

    $this->get(route('admin.books.edit', ['id' => $book->id]))->assertOk();
    $this->get(route('admin.authors.edit', ['id' => $author->id]))->assertOk();
    $this->get(route('admin.categories.edit', ['id' => $category->id]))->assertOk();
    $this->get(route('admin.banners.edit', ['id' => $banner->id]))->assertOk();
    $this->get(route('admin.users.edit', ['id' => $admin->id]))->assertOk();
    $this->get(route('admin.admins.edit', ['id' => $admin->id]))->assertOk();
    $this->get(route('admin.orders.edit', ['id' => $order->id]))->assertOk();
});
