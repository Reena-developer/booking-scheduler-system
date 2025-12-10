<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponseTrait;
    
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return $this->error(
                'Invalid email or password', 
                config('api.status.unauthorized')
            );
        }

        $user = $request->user();

        $token = $user->createToken('api_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user'  => new UserResource($user)
        ], 'Login successful', config('api.status.success'));
    }
}
