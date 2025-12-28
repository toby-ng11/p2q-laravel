<?php

namespace App\Http\Requests;

use App\Models\Architect;
use Illuminate\Foundation\Http\FormRequest;

class StoreArchitectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->can('create', Architect::class);
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
            'architect_rep_id' => 'required',
            'architect_type_id' => 'required',
            'class_id' => 'nullable|string|max:1',
        ];
    }
}
