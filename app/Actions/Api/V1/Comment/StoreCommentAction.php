<?php

declare(strict_types=1);

namespace App\Actions\Api\V1\Comment;

use App\Actions\Images\StoreImageAction;
use App\Http\Requests\Api\V1\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
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
     * @param  int  $commentableId
     * @param  string  $commentableType
     * @return Comment
     * @throws ReflectionException
     */
    public function __invoke(
        int $commentableId,
        string $commentableType
    ): Comment {
        $comment = Comment::create([
            'commentable_id' => $commentableId,
            'commentable_type' => $commentableType,
            'user_id' => $this->user->id,
            'text' => $this->request->validated('text'),
        ]);

        $this->request->whenHas('images', function (array $images) use ($comment) {
            $this->storeImageAction->storeMany(
                files: $images,
                id: $comment->id,
                type: Comment::class,
            );
        });

        return $comment->load([
            'user:id,name',
            'images'
        ]);
    }
}
