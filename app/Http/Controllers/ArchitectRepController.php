<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArchitectRepController extends Controller
{
    public function __construct(protected UserService $userService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        $user = $request->user();

        if (!$user || ! $user->user_role_id->atLeast(UserRole::ARCHREP)) {
            abort(403);
        }

        return $this->userService->fetchArchitectRepsByUserRole($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): ResourceCollection
    {
        if (! $user->user_role_id->atLeast(UserRole::ARCHREP)) {
            abort(403);
        }

        return UserResource::collection([$user]);
    }
}
