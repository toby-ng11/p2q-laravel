<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $key): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $pref = UserPreference::where('user_id', $user->id)
                ->where('key', $key)
                ->first();

            return response()->json($pref?->value ?? []);
        } else {
            return response()->json(['User is not authenticated.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $key): JsonResponse
    {
        $user = $request->user();
        UserPreference::updateOrCreate(
            ['user_id' => $user->id, 'key' => $key],
            ['value' => $request->input('value')]
        );

        return response()->json(['success' => true]);
    }
}
