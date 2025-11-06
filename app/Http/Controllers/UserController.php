<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"User"},
     *     summary="Get all users",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of users")
     * )
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', User::class);
            return response()->json(['data' => User::all()]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning('User list authorization failed', [
                'auth_user_id' => auth()->id(),
                'exception' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Unauthorized to view users'], 403);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"User"},
     *     summary="Get user by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User detail")
     * )
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('view', $user);
        return response()->json($user);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"User"},
     *     summary="Create a new user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="role", type="string", enum={"admin","user"})
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created")
     * )
     */
    public function store(Request $request)
    {
        try {
            $this->authorize('create', User::class);

            $validated = $request->validate([
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6',
                'role' => 'required|string|in:admin,user',
            ]);

            $validated['password'] = bcrypt($validated['password']);
            $user = User::create($validated);

            return response()->json(['message' => 'User created', 'user' => $user], 201);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning('User create authorization failed', [
                'auth_user_id' => auth()->id(),
                'exception' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Unauthorized to create users'], 403);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"User"},
     *     summary="Update user by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="role", type="string", enum={"admin","user"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $this->authorize('update', $user);

            $validated = $request->validate([
                'name' => 'sometimes|string|between:2,100',
                'email' => 'sometimes|string|email|max:100|unique:users,email,'.$user->id,
                'password' => 'sometimes|string|min:6',
                'role' => 'sometimes|string|in:admin,user',
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }

            $user->update($validated);

            return response()->json(['message' => 'User updated', 'user' => $user]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning('User update authorization failed', [
                'auth_user_id' => auth()->id(),
                'target_user_id' => $id,
                'exception' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Unauthorized to update this user'], 403);
        } catch (\Exception $e) {
            \Log::error('User update error', [
                'user_id' => $id,
                'exception' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"User"},
     *     summary="Delete user by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User deleted")
     * )
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $this->authorize('delete', $user);
            $user->delete();

            return response()->json(['message' => 'User deleted']);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning('User delete authorization failed', [
                'auth_user_id' => auth()->id(),
                'target_user_id' => $id,
                'exception' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Unauthorized to delete this user'], 403);
        } catch (\Exception $e) {
            \Log::error('User delete error', [
                'user_id' => $id,
                'exception' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to delete user'], 500);
        }
    }
}