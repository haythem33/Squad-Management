<?php

namespace App\Http\Requests;

use App\Models\Player;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLineupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'players' => ['nullable', 'array'],
            'players.*' => ['nullable', 'array'],
            'players.*.selected' => ['nullable', 'boolean'],
            'players.*.goals' => ['nullable', 'integer', 'min:0'],
            'players.*.minutes' => ['nullable', 'integer', 'min:0', 'max:120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'players.array' => 'Players data must be an array.',
            'players.*.goals.integer' => 'Goals must be a number.',
            'players.*.goals.min' => 'Goals cannot be negative.',
            'players.*.minutes.integer' => 'Minutes played must be a number.',
            'players.*.minutes.min' => 'Minutes played cannot be negative.',
            'players.*.minutes.max' => 'Minutes played cannot exceed 120.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure players is always an array
        if (!$this->has('players')) {
            $this->merge(['players' => []]);
        }
    }
}
