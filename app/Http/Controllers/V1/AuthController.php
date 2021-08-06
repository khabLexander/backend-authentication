<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Authentications\LoginRequest;

use App\Http\Resources\V1\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::firstWhere('username', '=', $request->username);
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encotrado'
            ], 404);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Contraseña incorrecta'
            ], 401);
        }

        return (new UserResource($user))->additional([
            'token' => $user->createToken($request->username)->plainTextToken,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'logout'
        ], 200);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'logoutAll'
        ], 200);
    }
}
