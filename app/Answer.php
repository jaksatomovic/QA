<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use Searchable;

    /**
     * Auto-apply mass assignment protection
     *
     * @var array
     */
    protected $fillable = [
        'body', 'user_id'
    ];

    /**
     * The accessors to append to the model's array form
     *
     * @var array
     */
    protected $appends = [
        'created_ago', 'body_text', 'body_html', 'is_accepted'
    ];

    /**
     * Boot the model
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($answer) {
            $answer->question->increment('answers_counted');
            $answer->user()->increment('points');
        });

        static::deleted(function ($answer) {
            $question = $answer->question;
            $question->decrement('answers_counted');
            if ($answer->user->points > 0) {
                $answer->user()->decrement('points');
            }
            if ($question->best_answer_id == $answer->id) {
                $question->removeBestAnswer();
            }
        });
    }

    /**
     * Get the indexable data array for the model
     * for TNT Search Driver
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return array_only($this->toArray(), ['id', 'body']);
    }

    /**
     * Apply all relevant answer filters
     *
     * @param   Builder         $query
     * @param   AnswerFilters   $filters
     * @return  Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * An answer belongs to a question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * An answer belongs to an user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An answer may have votes
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphToMany
     */
    public function votes()
    {
        return $this->morphToMany(User::class, 'vote')
            ->withTimestamps();
    }

    /**
     * Access human time difference for answer's created time
     *
     * @return string
     */
    public function getCreatedAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Access to cleaned HTML body
     *
     * @return string
     */
    public function getBodyHTMLAttribute()
    {
        return clean($this->body);
    }

    /**
     * Access to cleaned text body
     *
     * @return string
     */
    public function getBodyTextAttribute()
    {
        return strip_tags($this->body);
    }

    /**
     * Return if answer is accepted as best answer for parent question
     *
     * @return boolean
     */
    public function getisAcceptedAttribute()
    {
        return ($this->id == $this->question->best_answer_id) ? true : false;
    }
}
