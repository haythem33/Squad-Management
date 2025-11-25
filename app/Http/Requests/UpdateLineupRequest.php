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
            'players' => ['required', 'array'],
            'players.*' => ['required', 'array'],
            'players.*.id' => ['required', 'exists:players,id'],
            'players.*.goals' => ['required', 'integer', 'min:0'],
            'players.*.minutes_played' => ['required', 'integer', 'min:0', 'max:120'],
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
            'players.required' => 'At least one player must be selected.',
            'players.array' => 'Players data must be an array.',
            'players.*.id.required' => 'Player ID is required.',
            'players.*.id.exists' => 'One or more selected players do not exist.',
            'players.*.goals.required' => 'Goals are required for each player.',
            'players.*.goals.integer' => 'Goals must be a number.',
            'players.*.goals.min' => 'Goals cannot be negative.',
            'players.*.minutes_played.required' => 'Minutes played are required for each player.',
            'players.*.minutes_played.integer' => 'Minutes played must be a number.',
            'players.*.minutes_played.min' => 'Minutes played cannot be negative.',
            'players.*.minutes_played.max' => 'Minutes played cannot exceed 120.',
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
