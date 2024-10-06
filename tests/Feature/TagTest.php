<?php


use App\Models\Tag;

use function Pest\Laravel\getJson;

describe('tags tests', function () {
    beforeEach(function () {
        $this->tags = Tag::factory(15)->create();
    });

    it('get tags', function () {
        /** @var Tag $tag */
        $tag = $this->tags->random();

        getJson("api/v1/tags")
            ->assertSuccessful()
            ->assertSee($tag->toArray());
    });
});
