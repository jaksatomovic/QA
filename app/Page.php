<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * Auto-apply mass assignment protection
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'body', 'is_published'
    ];

    /**
     * Apply all relevant page filters
     *
     * @param   Builder         $query
     * @param   PageFilters     $filters
     * @return  Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
