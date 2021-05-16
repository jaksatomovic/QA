<?php

namespace App;

use Carbon\Carbon;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use Searchable;

    /**
     * Auto-apply mass assignment protection
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body'
    ];

    /**
     * The accessors to append to the model's array form
     *
     * @var array
     */
    protected $appends = [
        'path', 'body_text', 'body_html', 'created_ago',
        'edited_ago', 'is_favorited', 'model_type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'edited_at'
    ];

    /**
     * Boot the model
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($question) {
            $question->user()->increment('points');
        });

        static::deleted(function ($question) {
            if ($question->user->points > 0) {
                $question->user()->decrement('points');
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
        return array_only($this->toArray(), ['id', 'title', 'body']);
    }

    /**
     * Apply all relevant question filters
     *
     * @param   Builder          $query
     * @param   QuestionFilters  $filters
     * @return  Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * A question is assigned to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A question may have many answers
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * A question may be favorited by many users
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    /**
     * A question may be favorited by auth user
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function favorited()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->where('user_id', '=', \Auth::id());
    }

    /**
     * A question may have votes
     *
     * @return  \Illuminate\Database\Eloquent\Relations\morphToMany
     */
    public function votes()
    {
        return $this->morphToMany(User::class, 'vote')
            ->withTimestamps();
    }

    /**
     * A question may be assigned to many topics
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class)
            ->withTimestamps();
    }

    /**
     * Set the proper slug attribute with title
     *
     * @param   string $value
     */
    public function setTitleAttribute(string $title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = makeSlugFromTitle('\App\Question', $title);
    }

    /**
     * Access slug attribute for question
     *
     * @return  string
     */
    public function getPathAttribute()
    {
        return ($this->slug) ? route('question', $this->slug) : null;
    }

    /**
     * Access human time difference for question's created time
     *
     * @return  string
     */
    public function getCreatedAgoAttribute()
    {
        return ($this->created_at) ? $this->created_at->diffForHumans() : null;
    }

    /**
     * Access human time difference for question's edited time
     *
     * @return  string
     */
    public function getEditedAgoAttribute()
    {
        return ($this->edited_at) ? $this->edited_at->diffForHumans() : null;
    }

    /**
     * Access to cleaned HTML body
     *
     * @return  string
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
     * Set model type
     *
     * @param   string $value
     */
    public function getModelTypeAttribute()
    {
        return 'Question';
    }

    /**
     * Mark the given answer as the best
     *
     * @param Answer $answer
     */
    public function acceptBestAnswer(Answer $answer)
    {
        $answer->question->best_answer_id = $answer->id;
        $answer->question->save();
    }

    /**
     * Remove best answer value
     */
    public function removeBestAnswer()
    {
        $this->best_answer_id = null;
        $this->save();
    }

    /**
     * Return if question is favorited by auth user
     * Will return true only if is used eager load "favorited"
     *
     * @return  boolean
     */
    public function getIsFavoritedAttribute()
    {
        return (array_key_exists('favorited', $this->relations)) ? $this->favorited->count() > 0 : false;
    }

    /**
     * Updating question's favorites counter
     *
     * @param   Question $question
     * @return  int - number of users who added question in favorites
     */
    public function updateFavoritesCounter(Question $question)
    {
        $question->favorites_counted = $question->favorites()->count();
        $question->save();

        return $question->favorites_counted;
    }

    /**
     * Updating edited timestamp
     *
     * @param   Question $question
     */
    public function touchEdited()
    {
        $this->edited_at = new Carbon();
        return $this->save();
    }
}
