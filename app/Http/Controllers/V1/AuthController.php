<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Authentications\LoginRequest;

use App\Http\Resources\V1\Authentications\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\isNull;


class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function login(LoginRequest $request)
    {
        $user = User::firstWhere('username', $request->username);

        if (!$user) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario no existe',
                    'detail' => 'Intente de nuevo',
                    'code' => '404'
                ]
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'msg' => [
                    'summary' => 'success',
                    'detail' => '',
                    'code' => '200'
                ]
            ], 401);
        }

        return (new AuthResource($user))->additional([
            'token' => $user->createToken($request->getClientIp())->plainTextToken,
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
            'msg' => [
                'summary' => 'logout',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'msg' => [
                'summary' => 'logoutAll',
                'detail' => '',
                'code' => '200'
            ]
        ], 200);
    }
}
