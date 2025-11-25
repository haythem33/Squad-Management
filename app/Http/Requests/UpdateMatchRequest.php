<?php

namespace App\Http\Requests;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMatchRequest extends FormRequest
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
            'opponent' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'in:Home,Away'],
            'venue' => ['nullable', 'string', 'max:255'],
            'team_score' => ['nullable', 'integer', 'min:0'],
            'opponent_score' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'in:scheduled,completed,cancelled'],
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
            'team_id.required' => 'A team must be selected.',
            'team_id.exists' => 'The selected team does not exist.',
            'opponent.required' => 'The opponent name is required.',
            'match_date.required' => 'The match date is required.',
            'match_date.date' => 'The match date must be a valid date.',
            'location.in' => 'The location must be either Home or Away.',
            'status.in' => 'The status must be scheduled, completed, or cancelled.',
            'team_score.min' => 'Team score cannot be negative.',
            'opponent_score.min' => 'Opponent score cannot be negative.',
        ];
    }
}
