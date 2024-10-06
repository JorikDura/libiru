<?php

declare(strict_types=1);

namespace App\Actions\Images;

use App\Http\Requests\Api\V1\Image\DeleteImageRequest;
use App\Models\Image;
use Symfony\Component\HttpFoundation\Response;

final readonly class DeleteImageActionByIdAction
{
    public function __construct(
        private DeleteImageRequest $request
    ) {
    }

    /**
     * @param  mixed  $model
     * @return void
     */
    public function __invoke(
        mixed $model
    ): void {
        /** @var Image $image */
        $image = $model->images()->find($this->request->validated('id'))
            ?? abort(
                code: Response::HTTP_NOT_FOUND,
                message: __('messages.no_image_found_for_current_model')
            );

        $image->delete();
    }
}
