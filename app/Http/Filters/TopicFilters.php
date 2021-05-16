<?php

namespace App\Http\Filters;

use App\Http\Filters\Filters;

class TopicFilters extends Filters
{
    /**
     * Default order by value for topics
     */
    protected $defaultOrderBy = 'most_followed';

    /**
     * Available filters for topics
     */
    protected $filters = [
        'order_by', 'only', 'followed_by', 'spaces', 'topics', 'has'
    ];

    /**
     * Filter by spaces
     *
     * @return string
     */
    public function spaces()
    {
        $this->builder->where('is_space', 1);
    }

    /**
     * Filter by spaces
     *
     * @return string
     */
    public function topics()
    {
        $this->builder->where('is_space', 0);
    }

    /**
     * Filter only topics which match filter
     *
     * @param   string $type
     * @return  string
     */
    public function only(string $type)
    {
        switch ($type) {
            case 'followed':
                $this->builder->whereHas('followers', function ($query) {
                    $query->where('user_id', '=', auth()->id());
                });
                break;
            case 'owner':
                $this->builder->where('user_id', '=', auth()->id());
                break;
            default:
                $this->builder;
                break;
        }
    }

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
