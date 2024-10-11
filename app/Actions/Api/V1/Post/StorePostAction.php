<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Actions\Images\StoreImageAction;
use App\Http\Requests\Api\V1\Post\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;

final readonly class StorePostAction
{
    public function __construct(
        #[CurrentUser] private User $user,
        private StorePostRequest $request,
        private StoreImageAction $storeImageAction
    ) {
    }

    /**
     * @return Post
     */
    public function __invoke(): Post
    {
        return DB::transaction(function () {
            $post = Post::create([
                'title' => $this->request->validated('title'),
                'text' => $this->request->validated('text'),
                'user_id' => $this->user->id
            ]);

            $this->request->whenHas('tags', function (array $tags) use ($post) {
                $tagsIds = collect($tags)->map(function (string $tag) {
                    return Tag::firstOrCreate([
                        'name' => $tag
                    ])->id;
                });
                $post->tags()->attach($tagsIds);
            });

            $this->request->whenHas('related_books', fn (array $books) => $post->books()->attach($books));

            $this->request->whenHas('related_people', fn (array $people) => $post->people()->attach($people));

            $this->request->whenHas('images', fn (array $images) => $this->storeImageAction->storeMany(
                files: $images,
                model: $post
            ));

            return $post->loadFullPost();
        });
    }
}
