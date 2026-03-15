<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_text',
        'question_date',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'question_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get all votes for the question.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get the yes votes count.
     */
    public function getYesCountAttribute(): int
    {
        return $this->votes()->where('vote_option', 'yes')->count();
    }

    /**
     * Get the no votes count.
     */
    public function getNoCountAttribute(): int
    {
        return $this->votes()->where('vote_option', 'no')->count();
    }

    /**
     * Get the no comment votes count.
     */
    public function getNoCommentCountAttribute(): int
    {
        return $this->votes()->where('vote_option', 'no_comment')->count();
    }

    /**
     * Get the total votes count.
     */
    public function getTotalVotesAttribute(): int
    {
        return $this->votes()->count();
    }

    /**
     * Get the yes votes percentage.
     */
    public function getYesPercentageAttribute(): float
    {
        return $this->total_votes > 0 
            ? round(($this->yes_count / $this->total_votes) * 100, 2) 
            : 0;
    }

    /**
     * Get the no votes percentage.
     */
    public function getNoPercentageAttribute(): float
    {
        return $this->total_votes > 0 
            ? round(($this->no_count / $this->total_votes) * 100, 2) 
            : 0;
    }

    /**
     * Get the no comment votes percentage.
     */
    public function getNoCommentPercentageAttribute(): float
    {
        return $this->total_votes > 0 
            ? round(($this->no_comment_count / $this->total_votes) * 100, 2) 
            : 0;
    }

    /**
     * Get all vote options with counts and percentages.
     */
    public function getVoteOptionsAttribute(): array
    {
        return [
            [
                'value' => 'yes',
                'text' => 'হাঁয়া ভোট',
                'count' => $this->yes_count,
                'percentage' => $this->yes_percentage,
            ],
            [
                'value' => 'no',
                'text' => 'না ভোট',
                'count' => $this->no_count,
                'percentage' => $this->no_percentage,
            ],
            [
                'value' => 'no_comment',
                'text' => 'মন্তব্য নেই',
                'count' => $this->no_comment_count,
                'percentage' => $this->no_comment_percentage,
            ],
        ];
    }

    /**
     * Scope a query to only include active questions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive questions.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Activate the question.
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Deactivate the question.
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Check if user has voted on this question.
     */
    public function hasUserVoted(?int $userId = null, ?string $ipAddress = null): bool
    {
        $query = $this->votes();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($ipAddress) {
            $query->where('ip_address', $ipAddress);
        }

        return $query->exists();
    }

    /**
     * Get user's vote for this question.
     */
    public function getUserVote(?int $userId = null, ?string $ipAddress = null): ?Vote
    {
        $query = $this->votes();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($ipAddress) {
            $query->where('ip_address', $ipAddress);
        }

        return $query->first();
    }
}