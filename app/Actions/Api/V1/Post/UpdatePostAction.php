<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Post;

use App\Actions\Images\StoreImageAction;
use App\Http\Requests\Api\V1\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

final readonly class UpdatePostAction
{
    public function __construct(
        private UpdatePostRequest $request,
        private StoreImageAction $storeImageAction
    ) {
    }

    /**
     * @param  Post  $post
     * @return Post
     */
    public function __invoke(Post $post): Post
    {
        return DB::transaction(function () use ($post) {
            $this->request->whenHas('title', function (string $title) use ($post) {
                $post->title = $title;
            });

            $this->request->whenHas('text', function (string $text) use ($post) {
                $post->text = $text;
            });

            $post->save();

            $this->request->whenHas('tags', function (array $tags) use ($post) {
                $tagsIds = collect($tags)->map(function (string $tag) {
                    return Tag::firstOrCreate([
                        'name' => $tag
                    ])->id;
                });
                $post->tags()->sync($tagsIds);
            });

            $this->request->whenHas('related_books', fn (array $books) => $post->books()->sync($books));

            $this->request->whenHas('related_people', fn (array $people) => $post->people()->sync($people));

            $this->request->whenHas('images', fn (array $images) => $this->storeImageAction->storeMany(
                files: $images,
                id: $post->id,
                type: Post::class
            ));

            return $post->loadFullPost();
        });
    }
}
