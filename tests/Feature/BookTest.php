<?php

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
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('book tests', function () {
    beforeEach(function () {
        $publisher = Publisher::factory()->create();

        $this->books = Book::factory(15)->create([
            'publisher_id' => $publisher->id
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

        $test = postJson(
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

        $test = postJson(
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

    it('delete book', function () {
        /** @var Book $book */
        $book = $this->books->random();

        $imageData = [
            'imageable_id' => $book->id,
            'imageable_type' => Book::class,
            'original_image' => 'test.jpg',
            'preview_image' => 'test-scaled.jpg'
        ];

        Image::factory()->create($imageData);

        deleteJson(uri: "api/v1/books/$book->id")
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
});
