<?php

use App\Models\Image;
use App\Models\Publisher;
use Tests\TestHelpers;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('publishers tests', function () {
    beforeEach(function () {
        $this->publishers = Publisher::factory(15)->create();
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

        $test = postJson(
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

    it('update publisher', function () {
        Storage::fake('public');

        /** @var Publisher $publisher */
        $publisher = $this->publishers->random();

        $data = [
            'name' => fake()->name,
            'image' => TestHelpers::uploadFile('image.jpg'),
            '_method' => 'PUT'
        ];

        postJson(
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

    it('delete publisher', function () {
        $publisher = $this->publishers->random();

        $imageData = [
            'imageable_id' => $publisher->id,
            'imageable_type' => Publisher::class,
            'original_image' => 'test.jpg',
            'preview_image' => 'test-scaled.jpg'
        ];

        Image::factory()->create($imageData);

        deleteJson(uri: "api/v1/publishers/$publisher->id")
            ->assertSuccessful()
            ->assertNoContent();

        assertDatabaseMissing(
            table: 'publishers',
            data: $publisher->toArray()
        );

        assertDatabaseMissing(
            table: 'images',
            data: $imageData
        );
    });
});
