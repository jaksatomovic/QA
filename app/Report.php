<?php

namespace App;

use App\Question;
use App\Answer;
use App\Topic;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * Auto-apply mass assignment protection
     *
     * @var array
     */
    protected $fillable = [
        'report_id', 'report_type', 'user_id', 'message', 'is_solved'
    ];

    /**
     * The accessors to append to the model's array form
     *
     * @var array
     */
    protected $appends = [
        'created_ago', 'updated_ago', 'withModel'
    ];

    /**
     * Get the question attached to report record.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function questionReported()
    {
        return $this->hasOne(Question::class, 'id', 'report_id');
    }

    /**
     * Get the answer attached to report record.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function answerReported()
    {
        return $this->hasOne(Answer::class, 'id', 'report_id');
    }

    /**
     * Get the user attached to report record.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function userReported()
    {
        return $this->hasOne(User::class, 'id', 'report_id');
    }

    /**
     * Get the topic attached to report record.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function topicReported()
    {
        return $this->hasOne(Topic::class, 'id', 'report_id');
    }

    /**
     * A report is assigned to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Switching to relationship based on report_type column value
     *
     * @param   string  $query
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getWithModelAttribute($query)
    {
        if ($this->report_type == 'App\Question') {
            return $this->questionReported;
        } elseif ($this->report_type == 'App\Answer') {
            return $this->answerReported;
        } elseif ($this->report_type == 'App\Topic') {
            return $this->topicReported;
        } elseif ($this->report_type == 'App\User') {
            return $this->userReported;
        }

        return null;
    }

    /**
     * Access human time difference for report's created time
     *
     * @return  string
     */
    public function getCreatedAgoAttribute()
    {
        return ($this->created_at) ? $this->created_at->diffForHumans() : null;
    }

    /**
     * Access human time difference for report's updated time
     *
     * @return  string
     */
    public function getUpdatedAgoAttribute()
    {
        return ($this->updated_at) ? $this->updated_at->diffForHumans() : null;
    }

    /**
     * Apply all relevant question filters
     *
     * @param   Builder          $query
     * @param   ReportFilters    $filters
     * @return  Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
