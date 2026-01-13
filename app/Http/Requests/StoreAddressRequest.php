<?php

namespace App\Http\Requests;

use App\Models\Architect;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $phys_address1
 */
class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $architect = $this->route('architect') ?? '';

        if (! $architect || ! $user) {
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
            'name' => 'max:255',
            'phys_address1' => 'required|max:255',
            'phys_address2' => 'nullable|max:255',
            'phys_city' => 'nullable|max:255',
            'phys_state' => 'nullable|max:255',
            'phys_postal_code' => 'nullable|max:255',
            'phys_country' => 'nullable|max:255',
            'central_phone_number' => 'nullable|max:255',
            'email_address' => 'nullable|max:255',
            'url' => 'nullable|max:255',
        ];
    }
}
