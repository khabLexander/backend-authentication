<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Users\DestroysUserRequest;
use App\Http\Requests\V1\Users\StoreUserRequest;
use App\Http\Requests\V1\Users\UpdateUserRequest;
use App\Http\Resources\V1\Users\UserCollection;
use App\Http\Resources\V1\Users\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index()
    {
        return new UserCollection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->avatar = $request->input('avatar');
        $user->birthdate = $request->input('birthdate');
        $user->email = $request->input('email');
        $user->save();

        return response()->json(
            [
                'data' => $user,
                'msg' => [
                    'summary' => 'Usuario creado',
                    'detail' => '',
                    'code' => '201'
                ]
            ], 201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->avatar = $request->input('avatar');
        $user->username = $request->input('username');
        $user->birthdate = $request->input('birthdate');
        $user->email = $request->input('email');
        $user->email_verified_at = $request->input('email_verified_at');
        $user->password_changed = $request->input('password_changed');
        $user->save();
        return response()->json(
            [
                'data' => $user,
                'msg' => [
                    'summary' => 'Usuario Modificado',
                    'detail' => '',
                    'code' => '201'
                ]
            ], 201
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(
            [
                'data' => $user,
                'msg' => [
                    'summary' => 'Usuario Eliminado',
                    'detail' => '',
                    'code' => '201'
                ]
            ], 201
        );
    }

    public function destroys(DestroysUserRequest $request)
    {
        User::destroy($request->input('ids'));

        return response()->json(
            [
                'data' => null,
                'msg' => [
                    'summary' => 'Usuario/s Eliminado/s',
                    'detail' => '',
                    'code' => '201'
                ]
            ], 201
        );
    }
}
