<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Person;

use App\Actions\Api\V1\Person\IndexPersonAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PersonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexPersonController extends Controller
{
    /**
     * @param  Request  $request
     * @param  IndexPersonAction  $action
     * @return AnonymousResourceCollection
     */
    public function __invoke(
        Request $request,
        IndexPersonAction $action
    ): AnonymousResourceCollection {
        $people = $action($request);

        return PersonResource::collection($people);
    }
}
