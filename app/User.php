<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VoteAnswerNotification;
use App\Notifications\VoteQuestionNotification;

class User extends Authenticatable
{
    use Notifiable, Searchable;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'credentials',
        'about', 'location', 'picture', 'email_notifications'
    ];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form
     *
     * @var array
     */
    protected $appends = [
        'avatar', 'path', 'created_ago', 'about_html',
        'about_text', 'model_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];

    /**
     * Get the indexable data array for the model
     * for TNT Search Driver
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return array_only($this->toArray(), ['id', 'name']);
    }

    /**
     * Apply all relevant user filters
     *
     * @param   Builder       $query
     * @param   UserFilters   $filters
     * @return  Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Fetch all questions that were created by the user.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Fetch all spaces that were created by the user.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spaces()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Fetch all topics that were created by the user.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Fetch all answers that were created by the user.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * An user may add into favorites more questions
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(Question::class, 'favorites')
            ->withTimestamps();
    }

    /**
     * Fetch all reports that were created by the user.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * An user may follow more topics
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function followTopics()
    {
        return $this->belongsToMany(Topic::class, 'user_topic')
            ->where('is_space', '=', 0)
            ->withTimestamps();
    }

    /**
     * An user may follow more spaces
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function followSpaces()
    {
        return $this->belongsToMany(Topic::class, 'user_topic')
            ->where('is_space', '=', 1)
            ->withTimestamps();
    }

    /**
     * An user may follow more topics and spaces
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function followTopicsSpaces()
    {
        return $this->belongsToMany(Topic::class, 'user_topic')
            ->withTimestamps();
    }

    /**
     * An user can vote more questions
     *
     * @return  \Illuminate\Database\Eloquent\Relations\morphByMany
     */
    public function voteQuestions()
    {
        return $this->morphedByMany(Question::class, 'vote')
            ->withTimestamps();
    }

    /**
     * An user can vote more answers
     *
     * @return  \Illuminate\Database\Eloquent\Relations\morphByMany
     */
    public function voteAnswers()
    {
        return $this->morphedByMany(Answer::class, 'vote')
            ->withTimestamps();
    }

    /**
     * Access path attribute for user profile
     *
     * @return  string
     */
    public function getPathAttribute()
    {
        return route('user', $this->id);
    }

    /**
     * Access human time difference for question's created time
     *
     * @return  string
     */
    public function getCreatedAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Access profile picture attribute
     *
     * @return  string
     */
    public function getAvatarAttribute()
    {
        return ($this->picture) ? asset('storage/'.$this->picture) : '/images/avatar.png';
    }

    /**
     * Access to cleaned HTML body
     *
     * @return  string
     */
    public function getAboutHTMLAttribute()
    {
        return clean($this->about);
    }

    /**
     * Access to cleaned text body
     *
     * @return string
     */
    public function getAboutTextAttribute()
    {
        return strip_tags($this->about);
    }

    /**
     * Set model type
     *
     * @param   string $value
     */
    public function getModelTypeAttribute()
    {
        return 'User';
    }

    /**
     * Send Notification for vote
     *
     * @param   object  $model  Question/Answer
     * @param   string  $type
     */
    private function sendVoteNotification($model, $type)
    {
        if ($type == 'question') {
            $model->user->notify(new VoteQuestionNotification([
                'id' => $model->id,
                'question' => $model->title,
                'url' => $model->path
            ]));
        } elseif ($type == 'answer') {
            $model->user->notify(new VoteAnswerNotification([
                'id' => $model->id,
                'answer' => $model->body_text,
                'url' => $model->question->path
            ]));
        }
    }

    /**
     * Vote function
     *
     * @param   morphByMany relation    $relationship
     * @param   object                  $model      Question/Answer
     * @param   string                  $type       "question" / "answer"
     * @return  int                     $vote       -1 or +1 vote
     */
    private function _vote($relationship, $model, $vote, $type = '')
    {
        if ($relationship->where('vote_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        } else {
            $relationship->attach($model, ['vote' => $vote]);

            // Adding point to user only on first vote
            if ($vote > 0) {
                $model->user()->increment('points');
            } elseif ($model->user->points > 0) {
                $model->user()->decrement('points');
            }

            // Sending notification for first vote only
            $this->sendVoteNotification($model, $type);
        }

        $model->votes_counted = (int) $model->votes()->sum('vote');
        $model->save();

        return $model->votes_counted;
    }

    /**
     * Vote question function
     *
     * @param   Question    $question
     * @param   int         $vote - -1 or +1 vote
     * @return  int         number of votes
     */
    public function voteQuestion(Question $question, int $vote)
    {
        $voteQuestions = $this->voteQuestions();
        return $this->_vote($voteQuestions, $question, $vote, 'question');
    }

    /**
     * Vote answer function
     *
     * @param   Answer      $answer
     * @param   int         $vote - -1 or +1 vote
     * @return  int         number of votes
     */
    public function voteAnswer(Answer $answer, int $vote)
    {
        $voteAnswers = $this->voteAnswers();
        return $this->_vote($voteAnswers, $answer, $vote, 'answer');
    }
}
