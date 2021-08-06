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
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $users = User::name($request->input('search'))
                ->paginate($request->input('per_page'));
        } else {
            $users = User::paginate($request->input('per_page'));
        }

        return (new UserCollection($users))
            ->additional([
                'msg' => [
                    'summary' => 'success',
                    'detail' => '',
                    'code' => '200'
                ]
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->username = $request->input('username');
        $user->password = $request->input('password');
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->avatar = $request->input('avatar');
        $user->birthdate = $request->input('birthdate');
        $user->email = $request->input('email');
        $user->save();

        return (new UserResource($user))
            ->additional([
                'msg' => [
                    'summary' => 'Usuario Modificado',
                    'detail' => '',
                    'code' => '200'
                ]
            ]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        $user = $user->with('phones');
        return (new UserResource($user))
            ->additional([
                'msg' => [
                    'summary' => 'success',
                    'detail' => '',
                    'code' => '200'
                ]
            ]);;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->avatar = $request->input('avatar');
        $user->birthdate = $request->input('birthdate');
        $user->email = $request->input('email');
        $user->save();

        return (new UserResource($user))
            ->additional([
                'msg' => [
                    'summary' => 'Usuario Modificado',
                    'detail' => '',
                    'code' => '200'
                ]
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return UserResource
     */
    public function destroy(User $user)
    {
        $user->delete();
        return (new UserResource($user))
            ->additional([
                'msg' => [
                    'summary' => 'Usuario Eliminado',
                    'detail' => '',
                    'code' => '200'
                ]
            ]);
    }

    public function destroys(DestroysUserRequest $request)
    {
        $users = User::whereIn('id', $request->input('ids'))->get();
        User::destroy($request->input('ids'));

        return (new UserResource($users))
            ->additional([
                'msg' => [
                    'summary' => 'Usuarios Eliminados',
                    'detail' => '',
                    'code' => '201'
                ]
            ]);
    }
}
