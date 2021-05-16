<?php

namespace App\Http\Filters;

use App\Http\Filters\Filters;

class AnswerFilters extends Filters
{
    /**
     * Default order by value for answers
     */
    protected $defaultOrderBy = 'most_rated';

    /**
     * Available filters for answers
     */
    protected $filters = [
        'order_by', 'exclude', 'added_by', 'has'
    ];

    /**
     * Search by word filter
     *
     * @param   string  $query
     * @return  string
     */
    public function has(string $query)
    {
        $this->builder->where('body', 'LIKE', "%$query%");
    }
}
