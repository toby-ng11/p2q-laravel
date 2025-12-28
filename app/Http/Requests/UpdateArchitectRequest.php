<?php

namespace App\Http\Requests;

use App\Models\Architect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateArchitectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $architect = $this->route('architect') ?? '';
        $user = $this->user();

        if (! $architect || ! $user) {
            return false;
        }

        return $user->can('update', $architect);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'architect_name' => [
                'required',
                'max:255',
                Rule::unique('architects')->ignore($this->route('architect')),
            ],
            'architect_rep_id' => 'required',
            'architect_type_id' => 'required',
            'class_id' => 'nullable|string|max:1',
        ];
    }
}
