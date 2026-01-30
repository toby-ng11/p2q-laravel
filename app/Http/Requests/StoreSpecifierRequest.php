<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecifierRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'central_phone_number' => 'nullable|string|max:255',
            'email_address' => 'nullable|email|max:255',
        ];
    }
}
