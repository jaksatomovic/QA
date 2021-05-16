<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * Removing increment for notification id
     */
    public $incrementing = false;

    /**
     * The accessors to append to the model's array form
     *
     * @var array
     */
    protected $appends = [
        'created_ago'
    ];

    /**
     * Apply all relevant notifications filters
     *
     * @param   Builder         $query
     * @param   NotificationFilters   $filters
     * @return  Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * A notification belongs to an user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    /**
     * Access human time difference for notification's created time
     *
     * @return string
     */
    public function getCreatedAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
