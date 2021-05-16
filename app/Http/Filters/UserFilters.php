<?php

namespace App\Http\Filters;

use App\Http\Filters\Filters;

class UserFilters extends Filters
{
    /**
     * Default order by value for users
     */
    protected $defaultOrderBy = 'points';

    /**
     * Available filters for users
     */
    protected $filters = [
        'order_by', 'by_topic', 'exclude', 'has'
    ];

    /**
     * Filter by topic id
     *
     * @param int $topicId
     * @return string
     */
    public function by_topic(int $topicId)
    {
        $this->builder->whereHas('followTopicsSpaces', function ($q) use ($topicId) {
            $q->where('topic_id', $topicId);
        });
    }

    /**
     * Search by word filter
     *
     * @param   string  $query
     * @return  string
     */
    public function has(string $query)
    {
        $this->builder->where('name', 'LIKE', "%$query%")
            ->orWhere('credentials', 'LIKE', "%$query%")
            ->orWhere('id', 'LIKE', "%$query%");
    }
}
