<?php


use App\Enums\UserRole;
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

        $this->userAdmin = User::factory()->create([
            'role' => UserRole::ADMIN
        ]);

        $this->user = User::factory()->create([
            'role' => UserRole::USER
        ]);
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

        $test = actingAs($this->userAdmin)->postJson(
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

    it('simple user cannot store post', function () {
        actingAs($this->user)->postJson(
            uri: "api/v1/posts"
        )->assertForbidden();
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

        $test = actingAs($this->userAdmin)->postJson(
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

    it('simple user cannot update post', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        actingAs($this->user)->postJson(
            uri: "api/v1/posts/$post->id",
            data: ['_method' => 'PUT']
        )->assertForbidden();
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

        actingAs($this->userAdmin)->deleteJson(
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

    it('simple user cannot delete post', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        actingAs($this->user)->deleteJson(
            uri: "api/v1/posts/$post->id"
        )->assertForbidden();
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
        Storage::fake('public');

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

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text
        ];

        $comment = Comment::factory()->create($data);

        actingAs($this->user)->deleteJson(
            uri: "api/v1/posts/$post->id/comments/$comment->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'comments',
            data: $data
        );
    });

    it('admin deletes user comment', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text
        ];

        $comment = Comment::factory()->create($data);

        actingAs($this->userAdmin)->deleteJson(
            uri: "api/v1/posts/$post->id/comments/$comment->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'comments',
            data: $data
        );
    });

    it('simple user cannot delete another user comment', function () {
        /** @var Post $post */
        $post = $this->posts->random();

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'text' => fake()->text
        ];

        $newUser = User::factory()->create();

        $comment = Comment::factory()->create($data);

        actingAs($newUser)->deleteJson(
            uri: "api/v1/posts/$post->id/comments/$comment->id"
        )->assertForbidden();
    });
});
