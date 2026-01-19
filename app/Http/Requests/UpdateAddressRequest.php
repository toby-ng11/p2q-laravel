<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $phys_address1
 */
class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $architect = $this->route('architect') ?? '';
        $address = $this->route('address') ?? '';
        $user = $this->user();

        if (! $architect || ! $address || ! $user) {
            return false;
        }

        return $user->can('update', $architect);
    }

    /**
     * Prepare the data for validation.
     */
    #[\Override]
    protected function prepareForValidation(): void
    {
        if (empty($this->name)) {
            $this->merge([
                'name' => $this->phys_address1,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'phys_address1' => 'required|string|max:255',
            'phys_address2' => 'nullable|string|max:255',
            'phys_city' => 'nullable|string|max:255',
            'phys_state' => 'nullable|string|max:255',
            'phys_postal_code' => 'nullable|string|max:255',
            'phys_country' => 'nullable|string|max:255',
            'central_phone_number' => 'nullable|string|max:255',
            'email_address' => 'nullable|email|max:255',
            'url' => 'nullable|string|max:255',
        ];
    }
}
