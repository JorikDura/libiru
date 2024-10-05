<?php

use App\Models\Genre;

use function Pest\Laravel\getJson;

describe('genres', function () {
    it('get genres', function () {
        $genre = Genre::first();

        getJson(uri: "api/v1/genres")
            ->assertSuccessful()
            ->assertSee([
                'id' => $genre->id,
                'name' => $genre->name
            ]);
    });
});
