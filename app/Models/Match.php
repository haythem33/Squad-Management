<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GameMatch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'matches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_id',
        'opponent',
        'match_date',
        'location',
        'venue',
        'team_score',
        'opponent_score',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'match_date' => 'datetime',
    ];

    /**
     * Get the team that owns the match.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the players in this match's lineup.
     * Includes pivot data: goals, minutes_played
     */
    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'match_player')
            ->withPivot('goals', 'minutes_played')
            ->withTimestamps();
    }
}
