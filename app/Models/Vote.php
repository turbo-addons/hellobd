<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_id',
        'vote_option',
        'user_id',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vote_option' => 'string',
    ];

    /**
     * Get the question that owns the vote.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the user that owns the vote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include yes votes.
     */
    public function scopeYes($query)
    {
        return $query->where('vote_option', 'yes');
    }

    /**
     * Scope a query to only include no votes.
     */
    public function scopeNo($query)
    {
        return $query->where('vote_option', 'no');
    }

    /**
     * Scope a query to only include no_comment votes.
     */
    public function scopeNoComment($query)
    {
        return $query->where('vote_option', 'no_comment');
    }
}