<?php

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Person;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestHelpers;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;

describe('book tests', function () {
    beforeEach(function () {
        $publisher = Publisher::factory()->create();

        $this->books = Book::factory(15)->create([
            'publisher_id' => $publisher->id
        ]);

        $this->userAdmin = User::factory()->create([
            'role' => UserRole::ADMIN
        ]);

        $this->user = User::factory()->create([
            'role' => UserRole::USER
        ]);
    });

    it('get books', function () {
        /** @var Book $book */
        $book = $this->books->random();

        getJson(uri: '/api/v1/books')
            ->assertSuccessful()->assertSee([
                'id' => $book->id,
                'name' => $book->name,
                'russian_name' => $book->russian_name
            ]);
    });

    it('get books with filters', function () {
        /** @var Book $book */
        $book = $this->books->random();

        getJson(uri: "/api/v1/books?filter[book.name]=$book->name")
            ->assertSuccessful()->assertSee([
                'id' => $book->id,
                'name' => $book->name,
                'russian_name' => $book->russian_name
            ]);
    });

    it('get books by id', function () {
        /** @var Book $book */
        $book = $this->books->random();

        getJson(uri: "/api/v1/books/$book->id")
            ->assertSuccessful()->assertSee([
                'id' => $book->id,
                'name' => $book->name,
                'russian_name' => $book->russian_name
            ]);
    });

    it('store books', function () {
        Storage::fake('public');

        $data = [
            'publisher_id' => Publisher::factory()->create()->id,
            'russian_name' => fake()->name,
            'description' => fake()->text,
            'publish_date' => fake()->date,
            'images' => TestHelpers::randomUploadedFiles(),
            'authors' => [Person::factory()->create()->id],
            'translators' => [Person::factory()->create()->id],
            'genres' => [Genre::factory()->create()->id],
        ];

        $test = actingAs($this->userAdmin)->postJson(
            uri: '/api/v1/books',
            data: $data
        )->assertSuccessful()->assertSee([
            'russian_name' => $data['russian_name'],
            'description' => $data['description'],
        ]);

        assertDatabaseHas(
            table: 'books',
            data: [
                'russian_name' => $data['russian_name'],
                'description' => $data['description'],
            ]
        );

        /** @var Book $author */
        $book = $test->original;

        /** @var Image $image */
        $image = $book->images()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('simple user cannot store books', function () {
        actingAs($this->user)->postJson(
            uri: '/api/v1/books'
        )->assertForbidden();
    });

    it('update book', function () {
        Storage::fake('public');

        /** @var Book $book */
        $book = $this->books->random();

        $data = [
            'publisher_id' => Publisher::factory()->create()->id,
            'russian_name' => fake()->name,
            'description' => fake()->text,
            'publish_date' => fake()->date,
            'images' => TestHelpers::randomUploadedFiles(),
            'authors' => [Person::factory()->create()->id],
            'translators' => [Person::factory()->create()->id]
        ];

        $test = actingAs($this->userAdmin)->postJson(
            uri: "/api/v1/books/$book->id",
            data: $data + ['_method' => 'PUT']
        )->assertSuccessful()->assertSee([
            'russian_name' => $data['russian_name'],
            'description' => $data['description'],
        ]);

        assertDatabaseHas(
            table: 'books',
            data: [
                'russian_name' => $data['russian_name'],
                'description' => $data['description'],
            ]
        );

        /** @var Book $author */
        $book = $test->original;

        /** @var Image $image */
        $image = $book->images()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('simple user cannot update books', function () {
        /** @var Book $book */
        $book = $this->books->random();

        actingAs($this->user)->postJson(
            uri: "/api/v1/books/$book->id",
            data: ['_method' => 'PUT']
        )->assertForbidden();
    });

    it('delete book', function () {
        Storage::fake('public');

        /** @var Book $book */
        $book = $this->books->random();

        $original = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test.jpg'
        );

        $preview = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test-scaled.jpg'
        );

        $imageData = [
            'imageable_id' => $book->id,
            'imageable_type' => Book::class,
            'original_image' => $original,
            'preview_image' => $preview
        ];

        /** @var Image $image */
        $image = Image::factory()->create($imageData);

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image,
        ]);

        actingAs($this->userAdmin)
            ->deleteJson(uri: "api/v1/books/$book->id")
            ->assertSuccessful()
            ->assertNoContent();

        assertDatabaseMissing(
            table: 'books',
            data: $book->toArray()
        );

        assertDatabaseMissing(
            table: 'images',
            data: $imageData
        );

        Storage::disk('public')->assertMissing([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('simple user cannot delete book', function () {
        /** @var Book $book */
        $book = $this->books->random();

        actingAs($this->user)
            ->deleteJson(uri: "api/v1/books/$book->id")
            ->assertForbidden();
    });

    it('get book comments', function () {
        /** @var Book $book */
        $book = $this->books->random();

        $comments = Comment::factory(5)->create([
            'user_id' => $book->id,
            'commentable_id' => $book->id,
            'commentable_type' => Book::class
        ]);

        getJson(uri: "/api/v1/books/$book->id/comments")
            ->assertSuccessful()
            ->assertSee([
                'text' => $comments->random()->text
            ]);
    });

    it('store book comment', function () {
        /** @var Book $book */
        $book = $this->books->random();

        $user = User::factory()->create();

        $data = [
            'text' => fake()->text
        ];

        actingAs($user)->postJson(
            uri: "/api/v1/books/$book->id/comments",
            data: $data
        )->assertSuccessful()->assertSee([
            'text' => $data['text']
        ]);

        assertDatabaseHas(
            table: 'comments',
            data: [
                'commentable_id' => $book->id,
                'commentable_type' => Book::class,
                'text' => $data['text'],
            ]
        );
    });

    it('delete book comment', function () {
        /** @var Book $book */
        $book = $this->books->random();

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $book->id,
            'commentable_type' => Book::class,
            'text' => fake()->text
        ];

        $comment = Comment::factory()->create($data);

        actingAs($this->user)->deleteJson(
            uri: "api/v1/books/$book->id/comments/$comment->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'comments',
            data: $data
        );
    });

    it('admin deletes user comment', function () {
        /** @var Book $book */
        $book = $this->books->random();

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $book->id,
            'commentable_type' => Book::class,
            'text' => fake()->text
        ];

        $comment = Comment::factory()->create($data);

        actingAs($this->userAdmin)->deleteJson(
            uri: "api/v1/books/$book->id/comments/$comment->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'comments',
            data: $data
        );
    });

    it('simple user cannot delete another user comment', function () {
        /** @var Book $book */
        $book = $this->books->random();

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $book->id,
            'commentable_type' => Book::class,
            'text' => fake()->text
        ];

        $newUser = User::factory()->create();

        $comment = Comment::factory()->create($data);

        actingAs($newUser)->deleteJson(
            uri: "api/v1/books/$book->id/comments/$comment->id"
        )->assertForbidden();
    });
});
