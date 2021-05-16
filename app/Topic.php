<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use Searchable;

    /**
     * Auto-apply mass assignment protection
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body', 'picture', 'user_id',
        'is_space', 'is_approved'
    ];

    /**
     * The accessors to append to the model's array form
     *
     * @var array
     */
    protected $appends = [
        'path', 'body_text', 'body_html',
        'is_followed', 'created_ago', 'model_type'
    ];

    /**
     * Get the indexable data array for the model
     * for TNT Search Driver
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return array_only($this->toArray(), ['id', 'title']);
    }

    /**
     * Apply all relevant topic filters
     *
     * @param   Builder         $query
     * @param   TopicFilters    $filters
     * @return  Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * A topic may be assigned to many questions
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class)
            ->withTimestamps();
    }

    /**
     * A topic may be followed by many users
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_topic')
            ->withTimestamps();
    }

    /**
     * A topic is assigned to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A topic may be followed by auth user
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function followed()
    {
        return $this->belongsToMany(User::class, 'user_topic')
            ->where('user_id', '=', \Auth::id());
    }

    /**
     * Set the proper slug attribute with title
     *
     * @param   string  $value
     */
    public function setTitleAttribute(string $title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = makeSlugFromTitle('\App\Topic', $title);
    }

    /**
     * Access slug attribute for topic
     *
     * @return  string
     */
    public function getPathAttribute()
    {
        return route(($this->is_space == 1) ? 'space' : 'topic', $this->slug);
    }

    /**
     * Access attribute if auth user is following topic
     * Will return true only if is used eager load "followed"
     *
     * @return  string
     */
    public function getIsFollowedAttribute()
    {
        return (array_key_exists('followed', $this->relations)) ? $this->followed->count() > 0 : false;
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
     * Set model type
     *
     * @param   string $value
     */
    public function getModelTypeAttribute()
    {
        return ($this->is_space == 1) ? 'Space' : 'Topic';
    }

    /**
     * Access human time difference for space's created time
     *
     * @return  string
     */
    public function getCreatedAgoAttribute()
    {
        return ($this->created_at) ? $this->created_at->diffForHumans() : null;
    }

    /**
     * Access attribute for number of questions for topic
     *
     * @return string
     */
    public function getQuestionsCountedAttribute()
    {
        return $this->questions_count;
    }

    /**
     * Updating topic's follower counter
     *
     * @param   Topic   $topic
     * @return  int     number of followers
     */
    public function updateFollowersCounter(Topic $topic)
    {
        $topic->followers_counted = $topic->followers()->count();
        $topic->save();

        return $topic->followers_counted;
    }
}
