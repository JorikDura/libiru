<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SendEmailVerificationController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        abort_if(
            boolean: $user->hasVerifiedEmail(),
            code: Response::HTTP_FORBIDDEN,
            message: "Your email address is already verified."
        );

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification code has been sent!'
        ]);
    }
}
