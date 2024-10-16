<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Comment;

use App\Actions\Images\StoreImageAction;
use App\Http\Requests\Api\V1\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Model;
use ReflectionException;

final readonly class StoreCommentAction
{
    public function __construct(
        #[CurrentUser] private User $user,
        private StoreCommentRequest $request,
        private StoreImageAction $storeImageAction
    ) {
    }

    /**
     * @param  Model  $model
     * @return Comment
     * @throws ReflectionException
     */
    public function __invoke(Model $model): Comment
    {
        $comment = Comment::create([
            'commentable_id' => $model->getKey(),
            'commentable_type' => $model::class,
            'user_id' => $this->user->id,
            'text' => $this->request->validated('text'),
        ]);

        $this->request->whenHas('images', function (array $images) use ($comment) {
            $this->storeImageAction->storeMany(
                files: $images,
                model: $comment
            );
        });

        return $comment->load([
            'user:id,name',
            'images'
        ]);
    }
}
