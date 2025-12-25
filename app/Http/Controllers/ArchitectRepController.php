<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArchitectRepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || ! $user->user_role_id->atLeast(UserRole::ARCHREP)) {
            abort(403);
        }

        if ($user->isManagerOrAbove()) {
            return response()->json(
                User::query()
                    ->where('user_role_id', UserRole::ARCHREP)
                    ->select(['id', 'name'])
                    ->orderBy('name')
                    ->get()
            );
        }

        return response()->json([[
            'id' => $user->id,
            'name' => $user->name,
        ]]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        if (! $user->user_role_id->atLeast(UserRole::ARCHREP)) {
            abort(403);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
        ]);
    }
}
