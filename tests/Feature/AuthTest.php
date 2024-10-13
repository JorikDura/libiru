<?php

use App\Models\Book;
use App\Models\Image;
use App\Models\Person;
use App\Models\Publisher;
use App\Models\User;
use App\Notifications\EmailVerifyCodeNotification;
use App\Notifications\NewBookNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestHelpers;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;

describe('auth', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('register user', function () {
        Notification::fake();

        $data = [
            'name' => fake('en_GB')->name,
            'nickname' => fake('en_GB')->userName,
            'email' => fake()->email,
            'password' => $password = fake()->password(minLength: 8),
            'password_confirmation' => $password,
        ];

        postJson(
            uri: "api/v1/auth/registration",
            data: $data
        )->assertSuccessful()->assertSee(['token']);

        assertDatabaseHas(
            table: 'users',
            data: [
                'name' => $data['name'],
                'nickname' => $data['nickname'],
                'email' => $data['email']
            ]
        );

        $user = User::where([
            'name' => $data['name'],
            'nickname' => $data['nickname'],
            'email' => $data['email'],
        ])->first();

        Notification::assertSentTo($user, EmailVerifyCodeNotification::class);
    });

    it('login user', function () {
        User::factory()->create([
            'email' => $email = fake()->email,
            'password' => $password = fake()->password(minLength: 8)
        ]);

        postJson(
            uri: "api/v1/auth/login",
            data: [
                'email' => $email,
                'password' => $password
            ]
        )->assertSuccessful()->assertSee(['token']);
    });

    it('logout user', function () {
        /** @var User $user */
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        postJson(uri: "api/v1/auth/logout")
            ->assertSuccessful()
            ->assertNoContent();
    });

    it('upload user image', function () {
        Storage::fake('public');

        /** @var User $user */
        $user = User::factory()->create();

        actingAs($user)->postJson(
            uri: "api/v1/auth/image",
            data: [
                'image' => TestHelpers::uploadFile('image_test.jpg')
            ]
        )->assertSuccessful()->assertSee([
            'original_image_url',
            'preview_image_url'
        ]);

        assertDatabaseHas(
            table: 'images',
            data: [
                'imageable_id' => $user->id,
                'imageable_type' => User::class
            ]
        );

        /** @var Image $image */
        $image = $user->image()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('delete user image', function () {
        Storage::fake('public');

        Storage::put(
            path: 'images/users/',
            contents: TestHelpers::uploadFile('image_test.jpg')
        );
        Storage::put(
            path: 'images/users/',
            contents: TestHelpers::uploadFile('image_test-scaled.jpg')
        );

        /** @var User $user */
        $user = User::factory()->create();

        $image = Image::factory()->create([
            'imageable_id' => $user->id,
            'imageable_type' => User::class,
            'original_image' => 'images/users/image_test.jpg',
            'preview_image' => 'images/users/image_test-scaled.jpg'
        ]);

        actingAs($user)->deleteJson(
            uri: "api/v1/auth/image",
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'images',
            data: $image->toArray()
        );

        Storage::disk('public')->assertMissing([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('get notifications', function () {
        DatabaseNotification::create([
            'id' => fake()->uuid(),
            'type' => NewBookNotification::class,
            'notifiable_id' => $this->user->id,
            'notifiable_type' => User::class,
            'data' => [
                'text' => $text = fake()->text()
            ]
        ]);

        actingAs($this->user)
            ->getJson(uri: "api/v1/auth/notifications/")
            ->assertSuccessful()
            ->assertSee([
                'text' => $text
            ]);
    });

    it('new notification', function () {
        Notification::fake();

        $person = Person::factory()->create();

        $book = Book::factory()->create([
            'publisher_id' => Publisher::factory()->create()->id
        ]);

        $this->user->notify(new NewBookNotification($book, $person));

        Notification::assertSentTo($this->user, NewBookNotification::class);
    });

    it('mark notification as read', function () {
        Notification::fake();

        $notification = DatabaseNotification::create([
            'id' => fake()->uuid(),
            'type' => NewBookNotification::class,
            'notifiable_id' => $this->user->id,
            'notifiable_type' => User::class,
            'data' => [
                'text' => fake()->text()
            ]
        ]);

        actingAs($this->user)
            ->postJson(
                uri: "api/v1/auth/notifications/mark-as-read",
                data: [
                    'id' => [$notification->id]
                ]
            )
            ->assertSuccessful()
            ->assertNoContent();
    });

    it('delete notification', function () {
        Notification::fake();

        $notification = DatabaseNotification::create([
            'id' => fake()->uuid(),
            'type' => NewBookNotification::class,
            'notifiable_id' => $this->user->id,
            'notifiable_type' => User::class,
            'data' => [
                'text' => fake()->text()
            ]
        ]);

        actingAs($this->user)
            ->deleteJson(
                uri: "api/v1/auth/notifications/",
                data: [
                    'id' => [$notification->id]
                ]
            )
            ->assertSuccessful()
            ->assertNoContent();

        assertDatabaseMissing(
            table: 'notifications',
            data: $notification->toArray()
        );
    });
});
