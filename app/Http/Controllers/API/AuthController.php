<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{

    /*use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
    }*/

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()
                ->user()
                ->createToken('fifo')
                ->accessToken;

            return response()
                ->json([
                    'token' => $token,
                    'roles' => 'admin,user',
                    'user' => auth()->user()
                ], 200);
        } else {
            return response()->json(['message' => 'Those credentials weren\'t recognised, please try again.'], 401);
        }
    }

    /**
     * Handles the sending of a reset email to specified email address
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgot(Request $request)
    {

        return $this->sendResetLinkEmail($request);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Password reset email sent.'], 200);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {

        return response()->json(
            [
                'message' => 'We were unable to send the password reset.',
                'errors' => ['email' => trans($response)]
            ], 400
        );
    }

    /**
     * Handles the resetting of a user's password to specified password
     *
     * @param Request $request
     * @return JsonResponse
     */
    /*public function reset(Request $request)
    {
        return $this->reset($request);
    })*/

    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {

        $user->password = $password;
        $user->save();

        event(new PasswordReset($user));
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Password reset successfully, please use this password to login next time.'], 200);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'We were unable to reset the password', 'errors' => ['email' => trans($response)]], 400);
    }
}
