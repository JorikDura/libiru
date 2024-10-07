<?php

use App\Enums\UserRole;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Person;
use App\Models\User;
use Tests\TestHelpers;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;

describe('people tests', function () {
    beforeEach(function () {
        $this->people = Person::factory(15)->create();

        $this->userAdmin = User::factory()->create([
            'role' => UserRole::ADMIN
        ]);

        $this->user = User::factory()->create([
            'role' => UserRole::USER
        ]);
    });

    it('get people', function () {
        /** @var Person $person */
        $person = $this->people->random();

        getJson(
            uri: 'api/v1/people'
        )->assertSuccessful()->assertSee([
            'name' => $person->name,
            'russian_name' => $person->russian_name
        ]);
    });

    it('get people with filters', function () {
        /** @var Person $person */
        $person = $this->people->random();

        getJson(
            uri: "api/v1/people?filter[name]=$person->name"
        )->assertSuccessful()->assertSee([
            'name' => $person->name,
            'russian_name' => $person->russian_name
        ]);
    });

    it('get person', function () {
        $person = $this->people->random();

        getJson(
            uri: "api/v1/people/$person->id"
        )->assertSuccessful()->assertSee([
            'name' => $person->name
        ]);
    });

    it('store person', function () {
        Storage::fake('public');

        $data = [
            'name' => fake('en_GB')->name,
            'russian_name' => fake('en_GB')->name,
            'birth_date' => fake()->date(),
            'death_date' => fake()->date(),
            'description' => fake('en_GB')->text,
            'russian_description' => fake('en_GB')->text,
            'images' => TestHelpers::randomUploadedFiles(max: 5)
        ];

        $test = actingAs($this->userAdmin)->postJson(
            uri: 'api/v1/people',
            data: $data
        )->assertSuccessful()->assertSee([
            'name' => $data['name'],
            'russian_name' => $data['russian_name'],
            'birth_date' => $data['birth_date'],
            'death_date' => $data['death_date']
        ]);

        assertDatabaseHas(
            table: 'people',
            data: [
                'name' => $data['name'],
                'russian_name' => $data['russian_name'],
                'birth_date' => $data['birth_date'],
                'death_date' => $data['death_date']
            ]
        );

        /** @var Person $author */
        $person = $test->original;

        /** @var Image $image */
        $image = $person->images()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('simple user cannot store person', function () {
        actingAs($this->user)->postJson(
            uri: 'api/v1/people'
        )->assertForbidden();
    });

    it('update person', function () {
        Storage::fake('public');

        /** @var Person $person */
        $person = $this->people->random();

        $test = actingAs($this->userAdmin)->postJson(
            uri: "api/v1/people/$person->id",
            data: [
                '_method' => 'PUT',
                'name' => $name = fake('en_GB')->name,
                'russian_name' => $russianName = fake('en_GB')->name,
                'images' => TestHelpers::randomUploadedFiles(max: 5)
            ]
        )->assertSuccessful()->assertSee([
            'name' => $name,
            'russian_name' => $russianName
        ]);

        assertDatabaseHas(
            table: 'people',
            data: [
                'name' => $name,
                'russian_name' => $russianName
            ]
        );

        /** @var Person $author */
        $person = $test->original;

        /** @var Image $image */
        $image = $person->image()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('simple user cannot update person', function () {
        /** @var Person $person */
        $person = $this->people->random();

        actingAs($this->user)->postJson(
            uri: "api/v1/people/$person->id",
            data: ['_method' => 'PUT']
        )->assertForbidden();
    });

    it('delete person', function () {
        /** @var Person $person */
        $person = $this->people->random();

        $imageData = [
            'imageable_id' => $person->id,
            'imageable_type' => Person::class,
            'original_image' => 'test.jpg',
            'preview_image' => 'test-scaled.jpg'
        ];

        Image::factory()->create($imageData);

        actingAs($this->userAdmin)->deleteJson(
            uri: "api/v1/people/$person->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'people',
            data: $person->toArray()
        );

        assertDatabaseMissing(
            table: 'images',
            data: $imageData
        );
    });

    it('simple user cannot delete person', function () {
        /** @var Person $person */
        $person = $this->people->random();

        actingAs($this->user)->deleteJson(
            uri: "api/v1/people/$person->id"
        )->assertForbidden();
    });

    it('get person comments', function () {
        /** @var Person $person */
        $person = $this->people->random();

        $comments = Comment::factory(5)->create([
            'user_id' => $person->id,
            'commentable_id' => $person->id,
            'commentable_type' => Person::class
        ]);

        getJson(uri: "api/v1/people/$person->id/comments")
            ->assertSuccessful()
            ->assertSee([
                'text' => $comments->random()->text
            ]);
    });

    it('add person comments', function () {
        /** @var Person $person */
        $person = $this->people->random();

        $data = [
            'text' => fake()->text
        ];

        actingAs($this->user)->postJson(
            uri: "api/v1/people/$person->id/comments",
            data: $data
        )->assertSuccessful()->assertSee([
            'text' => $data['text']
        ]);

        assertDatabaseHas(
            table: 'comments',
            data: [
                'user_id' => $this->user->id,
                'commentable_id' => $person->id,
                'commentable_type' => Person::class,
                'text' => $data['text']
            ]
        );
    });

    it('delete person comment', function () {
        /** @var Person $person */
        $person = $this->people->random();

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $person->id,
            'commentable_type' => Person::class,
            'text' => fake()->text
        ];

        $comment = Comment::factory()->create($data);

        actingAs($this->user)->deleteJson(
            uri: "api/v1/people/$person->id/comments/$comment->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'comments',
            data: $data
        );
    });

    it('admin deletes user comment', function () {
        /** @var Person $person */
        $person = $this->people->random();

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $person->id,
            'commentable_type' => Person::class,
            'text' => fake()->text
        ];

        $comment = Comment::factory()->create($data);

        actingAs($this->userAdmin)->deleteJson(
            uri: "api/v1/people/$person->id/comments/$comment->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'comments',
            data: $data
        );
    });

    it('simple user cannot delete another user comment', function () {
        /** @var Person $person */
        $person = $this->people->random();

        $data = [
            'user_id' => $this->user->id,
            'commentable_id' => $person->id,
            'commentable_type' => Person::class,
            'text' => fake()->text
        ];

        $newUser = User::factory()->create();

        $comment = Comment::factory()->create($data);

        actingAs($newUser)->deleteJson(
            uri: "api/v1/people/$person->id/comments/$comment->id"
        )->assertForbidden();
    });
});
