<?php

namespace App\Http\Requests;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Verify that the team_id belongs to the authenticated user
        $team = Team::find($this->input('team_id'));
        
        return $team && $team->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'team_id' => ['required', 'exists:teams,id'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'jersey_number' => ['nullable', 'integer', 'min:1', 'max:99'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
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
            'name.required' => 'The player name is required.',
            'team_id.required' => 'A team must be selected.',
            'team_id.exists' => 'The selected team does not exist.',
            'jersey_number.min' => 'Jersey number must be at least 1.',
            'jersey_number.max' => 'Jersey number cannot exceed 99.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'photo.image' => 'The photo must be an image file.',
            'photo.max' => 'The photo must not be larger than 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'date_of_birth' => 'date of birth',
            'jersey_number' => 'jersey number',
        ];
    }
}
