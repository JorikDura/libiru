<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;

describe('user tests', function () {
    beforeEach(function () {
        $this->users = User::factory(15)->create();
    });

    it('get users', function () {
        /** @var User $user */
        $user = $this->users->random();

        getJson(uri: "api/v1/users")
            ->assertSuccessful()
            ->assertSee([
                'name' => $user->name
            ]);
    });

    it('get users with filters', function () {
        /** @var User $user */
        $user = $this->users->random();

        getJson(uri: "api/v1/users?filter[name]=$user->name")
            ->assertSuccessful()
            ->assertSee([
                'name' => $user->name
            ]);
    });

    it('get user by id', function () {
        /** @var User $user */
        $user = $this->users->random();

        getJson(uri: "api/v1/users/$user->id")
            ->assertSuccessful()
            ->assertSee([
                'name' => $user->name
            ]);
    });

    it('get user comments', function () {
        /** @var User $user */
        $user = $this->users->random();

        $comments = Comment::factory(5)->create([
            'user_id' => $user->id,
            'commentable_id' => $user->id,
            'commentable_type' => User::class
        ]);

        getJson(uri: "api/v1/users/$user->id/comments")
            ->assertSuccessful()
            ->assertSee([
                'text' => $comments->random()->text
            ]);
    });

    it('add user comments', function () {
        /** @var User $user */
        $user = $this->users->random();

        $data = [
            'text' => fake()->text
        ];

        actingAs($user)->postJson(
            uri: "api/v1/users/$user->id/comments",
            data: $data
        )->assertSuccessful()->assertSee([
            'text' => $data['text']
        ]);

        assertDatabaseHas(
            table: 'comments',
            data: [
                'user_id' => $user->id,
                'commentable_id' => $user->id,
                'commentable_type' => User::class,
                'text' => $data['text']
            ]
        );
    });
});
