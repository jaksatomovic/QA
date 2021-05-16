<?php

namespace App\Http\Filters;

use App\Http\Filters\Filters;

class QuestionFilters extends Filters
{
    /**
     * Default order by value for questions
     */
    protected $defaultOrderBy = 'latest';

    /**
     * Available filters for questions
     */
    protected $filters = [
        'order_by', 'by_topic', 'only', 'added_by', 'has'
    ];

    /**
     * Filter by topic id
     *
     * @param int $topicId
     * @return string
     */
    public function by_topic(int $topicId)
    {
        $this->builder->whereHas('topics', function ($q) use ($topicId) {
            $q->where('topic_id', $topicId);
        });
    }

    /**
     * Filter only questions which match filter
     *
     * @param string $type
     * @return string
     */
    public function only(string $type)
    {
        switch ($type) {
            case 'followed':
                $this->builder->whereHas('followers', function ($query) {
                    $query->where('user_id', '=', auth()->id());
                });
                break;
            case 'favorites':
                $this->builder->whereHas('favorites', function ($query) {
                    $query->where('user_id', '=', auth()->id());
                });
                break;
            case 'need_answer':
                $this->builder->where('answers_counted', '0');
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
