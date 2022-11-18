<?php

namespace App\Domains\Authentication\Services;

use App\Domains\Authentication\Actions\LoginUserAction;
use App\Domains\Authentication\Actions\LogoutUserAction;
use App\Domains\Authentication\Actions\RegisterUserAction;
use App\Domains\Authentication\Http\Requests\LoginUserRequest;
use App\Domains\Authentication\Http\Requests\RegisterUserRequest;
use App\Domains\Authentication\Http\Resources\UserAuthenticationResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{

    public function login(LoginUserRequest $request,string $roleName)
    {
        try {
            $user = (new LoginUserAction($request,$roleName))->execute();
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'success' => false
            ], 400);
        }
        return response()->json([
            'message' => 'User logged in successfully',
            'success' => true,
            'data' => new UserAuthenticationResource($user)
        ], 200);
    }

    public function register(RegisterUserRequest $request)
    {
        try {
            $user = (new RegisterUserAction($request))->execute();
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'success' => false
            ], 400);
        }
        return  response()->json([
            'message' => 'User register successfully',
            'success' => true,
            'data' => UserAuthenticationResource::make($user)
        ], 200);
    }

    public function logout(Request $request)
    {
        try {
            (new LogoutUserAction($request))->execute();

        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'success' => false
            ], 400);
        }

        return response()->json([
            'message' => 'Success',
            'success' => true,
        ]);
    }

    public function deactivateUser(Request $request)
    {
        try {

            Auth::user()->update(['is_active'=> $request->get('is_active',false)]);

        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'success' => false
            ], 400);
        }

        return response()->json([
            'message' => "success",
            'success' => true,
            'is_active' => $request->get('is_active')
        ]);
    }
}