<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::with('roles')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => UserResource::collection($users)
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'department' => $request->department,
            'position' => $request->position
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'status' => 'success',
            'message' => 'Utilisateur créé avec succès',
            'data' => new UserResource($user)
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $user->update($request->validated());

        if ($request->has('role')) {
            $user->syncRoles([$request->role]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Utilisateur mis à jour avec succès',
            'data' => new UserResource($user)
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }
}