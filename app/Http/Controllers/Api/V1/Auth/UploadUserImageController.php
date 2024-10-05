<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Images\StoreImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\UploadUserImageRequest;
use App\Http\Resources\Api\V1\ImageResource;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use ReflectionException;

class UploadUserImageController extends Controller
{
    /**
     * @param  StoreImageAction  $action
     * @param  User  $user
     * @param  UploadUserImageRequest  $request
     * @return ImageResource
     * @throws ReflectionException
     */
    public function __invoke(
        StoreImageAction $action,
        #[CurrentUser] User $user,
        UploadUserImageRequest $request
    ): ImageResource {
        $image = $user->image()->first();

        $image?->delete();

        $image = $action->store(
            file: $request->validated('image'),
            id: $user->id,
            type: User::class,
        );

        return ImageResource::make($image);
    }
}
