<?php

namespace App\Http\Filters;

use App\Http\Filters\Filters;

class ReportFilters extends Filters
{
    /**
     * Default order by value for answers
     */
    protected $defaultOrderBy = 'latest';

    /**
     * Available filters for answers
     */
    protected $filters = [
        'has'
    ];

    /**
     * Search by word filter
     *
     * @param   string  $query
     * @return  string
     */
    public function has(string $query)
    {
        $this->builder->where('message', 'LIKE', "%$query%");
    }
}
