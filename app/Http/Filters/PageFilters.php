<?php

namespace App\Http\Filters;

use App\Http\Filters\Filters;

class PageFilters extends Filters
{
    /**
     * Default order by value for topics
     */
    protected $defaultOrderBy = 'latest';

    /**
     * Available filters for topics
     */
    protected $filters = [
        'order_by', 'has'
    ];

    /**
     * Search by word filter
     *
     * @param   string  $query
     * @return  string
     */
    public function has(string $query)
    {
        $this->builder->where('title', 'LIKE', "%$query%")
            ->orWhere('body', 'LIKE', "%$query%");
    }
}
