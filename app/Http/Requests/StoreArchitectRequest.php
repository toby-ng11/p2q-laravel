<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\Architect;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StoreArchitectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user
            && $user->can('create', Architect::class)
            && $this->canAssignRep($user);
    }

    protected function canAssignRep(User $user): bool
    {
        if ($user->isManagerOrAbove()) {
            return true;
        }

        $architectRepId = $this->input('architect_rep_id');

        /** @var User|null */
        $submittedRep = User::find($architectRepId);

        // cannot assign archrep other than themself if user is an archrep
        return !($submittedRep && $submittedRep->id !== $user->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'architect_name' => 'required|max:255|unique:architects',
            'architect_rep_id' => [
                'required',
                Rule::exists('users', 'id')->where(function (Builder $query) {
                    $query->where('user_role_id', '>=', UserRole::ARCHREP);
                }),
            ],
            'architect_type_id' => 'required',
            'class_id' => 'nullable|string|max:1',
        ];
    }
}
