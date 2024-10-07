<?php

use App\Enums\UserRole;
use App\Models\Image;
use App\Models\Publisher;
use App\Models\User;
use Tests\TestHelpers;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;

describe('publishers tests', function () {
    beforeEach(function () {
        $this->publishers = Publisher::factory(15)->create();

        $this->userAdmin = User::factory()->create([
            'role' => UserRole::ADMIN
        ]);

        $this->user = User::factory()->create([
            'role' => UserRole::USER
        ]);
    });

    it('get publishers', function () {
        /** @var Publisher $publisher */
        $publisher = $this->publishers->random();

        getJson(uri: "api/v1/publishers")
            ->assertSuccessful()->assertSee([
                'name' => $publisher->name,
                'description' => $publisher->description,
                'russian_description' => $publisher->russian_description
            ]);
    });

    it('get publishers with filters', function () {
        /** @var Publisher $publisher */
        $publisher = $this->publishers->random();

        getJson(uri: "api/v1/publishers?filter[name]=$publisher->name")
            ->assertSuccessful()->assertSee([
                'name' => $publisher->name,
                'description' => $publisher->description,
                'russian_description' => $publisher->russian_description
            ]);
    });

    it('get publisher by id', function () {
        $publisher = $this->publishers->random();

        getJson(uri: "api/v1/publishers/$publisher->id")
            ->assertSuccessful()->assertSee([
                'name' => $publisher->name,
                'description' => $publisher->description,
                'russian_description' => $publisher->russian_description,
            ]);
    });

    it('store publisher', function () {
        Storage::fake('public');

        $data = [
            'name' => fake()->name,
            'description' => fake()->text,
            'russian_description' => fake()->text,
            'image' => TestHelpers::uploadFile('image.jpg')
        ];

        $test = actingAs($this->userAdmin)->postJson(
            uri: "api/v1/publishers",
            data: $data
        )->assertSuccessful()->assertSee([
            'name' => $data['name'],
            'description' => $data['description'],
            'russian_description' => $data['russian_description']
        ]);

        assertDatabaseHas(
            table: 'publishers',
            data: [
                'name' => $data['name'],
                'description' => $data['description'],
                'russian_description' => $data['russian_description']
            ]
        );

        /** @var Publisher $author */
        $publisher = $test->original;

        /** @var Image $image */
        $image = $publisher->image()->first();

        Storage::disk('public')->assertExists([
            $image->original_image,
            $image->preview_image
        ]);
    });

    it('simple user cannot store publisher', function () {
        actingAs($this->user)->postJson(
            uri: "api/v1/publishers"
        )->assertForbidden();
    });

    it('update publisher', function () {
        Storage::fake('public');

        /** @var Publisher $publisher */
        $publisher = $this->publishers->random();

        $data = [
            'name' => fake()->name,
            'image' => TestHelpers::uploadFile('image.jpg'),
            '_method' => 'PUT'
        ];

        actingAs($this->userAdmin)->postJson(
            uri: "api/v1/publishers/$publisher->id",
            data: $data
        )->assertSuccessful()->assertSee([
            'name' => $data['name'],
            'description' => $publisher->description,
            'russian_description' => $publisher->russian_description
        ]);

        assertDatabaseHas(
            table: 'publishers',
            data: [
                'name' => $data['name'],
                'description' => $publisher->description,
                'russian_description' => $publisher->russian_description
            ]
        );
    });

    it('simple user cannot update publisher', function () {
        /** @var Publisher $publisher */
        $publisher = $this->publishers->random();

        actingAs($this->user)->postJson(
            uri: "api/v1/publishers/$publisher->id",
            data: ['_method' => 'PUT']
        )->assertForbidden();
    });

    it('delete publisher', function () {
        /** @var Publisher $publisher */
        $publisher = $this->publishers->random();

        $imageData = [
            'imageable_id' => $publisher->id,
            'imageable_type' => Publisher::class,
            'original_image' => 'test.jpg',
            'preview_image' => 'test-scaled.jpg'
        ];

        Image::factory()->create($imageData);

        actingAs($this->userAdmin)->deleteJson(
            uri: "api/v1/publishers/$publisher->id"
        )->assertSuccessful()->assertNoContent();

        assertDatabaseMissing(
            table: 'publishers',
            data: $publisher->toArray()
        );

        assertDatabaseMissing(
            table: 'images',
            data: $imageData
        );
    });

    it('simple user cannot delete publisher', function () {
        /** @var Publisher $publisher */
        $publisher = $this->publishers->random();

        actingAs($this->user)->deleteJson(
            uri: "api/v1/publishers/$publisher->id"
        )->assertForbidden();
    });
});
