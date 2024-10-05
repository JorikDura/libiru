<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\VerifyEmailByCodeRequest;
use App\Models\User;
use App\Notifications\EmailVerifyCodeNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class VerifyEmailByCodeController extends Controller
{
    /**
     * @param  VerifyEmailByCodeRequest  $request
     * @return Response
     */
    public function __invoke(
        VerifyEmailByCodeRequest $request
    ): Response {
        /** @var User $user */
        $user = auth()->user() ?? abort(code: ResponseCode::HTTP_FORBIDDEN);

        $sentCode = Cache::get(EmailVerifyCodeNotification::VERIFICATION_KEY."_{$user->getKey()}") ?? abort(
            code: ResponseCode::HTTP_FORBIDDEN,
            message: "The code is outdated."
        );

        $receivedCode = $request->validated('code');

        abort_if(
            boolean: $receivedCode != $sentCode,
            code: ResponseCode::HTTP_FORBIDDEN,
            message: "Incorrect code."
        );

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            //calling event
            event(new Verified($user));
            Cache::forget("email_{$user->getKey()}");
        }

        return response()->noContent(ResponseCode::HTTP_OK);
    }
}
