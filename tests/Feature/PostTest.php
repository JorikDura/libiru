<?php


use App\Models\Book;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Person;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestHelpers;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;

describe('posts tests', function () {
    beforeEach(function () {
        $this->posts = Post::factory(15)->create();
        $this->user = User::factory()->create();
    });

    it('get posts', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        getJson('api/v1/posts')
            ->assertSuccessful()
            ->assertSee([
                'id' => $post->id,
                'title' => $post->title
            ]);
    });

    it('get post by id', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        getJson("api/v1/posts/$post->id")
            ->assertSuccessful()
            ->assertSee([
                'id' => $post->id,
                'title' => $post->title
            ]);
    });

    it('add post', function () {
        Storage::fake('public');

        $data = [
            'title' => fake()->title,
            'text' => fake()->text,
            'images' => TestHelpers::randomUploadedFiles(),
            'related_books' => Book::factory(3)->create()->pluck('id'),
            'related_people' => Person::factory(3)->create()->pluck('id'),
            'tags' => [fake()->word]
        ];

        $test = actingAs($this->user)->postJson(
            uri: "api/v1/posts",
            data: $data
        )->assertSuccessful()->assertSee([
            'title' => $data['title'],
            'text' => $data['text']
        ]);

        assertDatabaseHas(
            table: 'posts',
            data: [
                'title' => $data['title'],
                'text' => $data['text']
            ]
        );

        /** @var Post $author */
        $post = $test->original;

        /** @var Image $image */
        $image = $post->images()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('update post', function () {
        Storage::fake('public');

        $data = [
            'title' => fake()->title,
            'images' => TestHelpers::randomUploadedFiles(),
            'related_books' => Book::factory(3)->create()->pluck('id'),
            'related_people' => Person::factory(3)->create()->pluck('id'),
            'tags' => [fake()->word]
        ];

        /** @var Post $post */
        $post = $this->posts->random();

        $test = actingAs($this->user)->postJson(
            uri: "api/v1/posts/$post->id",
            data: $data + ['_method' => 'PATCH']
        )->assertSuccessful()->assertSee([
            'title' => $data['title'],
            'text' => $post->text
        ]);

        assertDatabaseHas(
            table: 'posts',
            data: [
                'title' => $data['title'],
                'text' => $post->text
            ]
        );

        /** @var Post $author */
        $post = $test->original;

        /** @var Image $image */
        $image = $post->images()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('delete post', function () {
        Storage::fake('public');

        /** @var Post $post */
        $post = $this->posts->random();

        $original = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test.jpg'
        );

        $preview = TestHelpers::storeFakeFiles(
            path: 'images/posts',
            name: 'test-scaled.jpg'
        );

        $imageData = [
            'imageable_id' => $post->id,
            'imageable_type' => Post::class,
            'original_image' => $original,
            'preview_image' => $preview
        ];

        /** @var Image $image */
        $image = Image::factory()->create($imageData);

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image,
        ]);

        actingAs($this->user)->deleteJson(
            uri: "api/v1/posts/$post->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'posts',
            data: $post->toArray()
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

    it('get post comments', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $comment = Comment::factory(15)->create([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ])->random();

        getJson("api/v1/posts/$post->id/comments")
            ->assertSuccessful()
            ->assertSee([
                'id' => $comment->id,
                'text' => $comment->text
            ]);
    });

    it('store post comment', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $data = [
            'text' => fake()->text,
            'images' => TestHelpers::randomUploadedFiles(max: 4),
        ];

        actingAs($this->user)->postJson(
            uri: "api/v1/posts/$post->id/comments",
            data: $data
        )->assertSuccessful()->assertSee([
            'text' => $data['text']
        ]);

        assertDatabaseHas(
            table: 'comments',
            data: [
                'text' => $data['text']
            ]
        );
    });

    it('delete post comment', function () {
        $post = $this->posts->random();

        $comment = Comment::factory()->create([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class
        ]);

        actingAs($this->user)->deleteJson(
            uri: "api/v1/posts/$post->id/comments/$comment->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'comments',
            data: [
                'text' => $comment->text
            ]
        );
    });
});
