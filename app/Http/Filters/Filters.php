<?php

namespace App\Http\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * The Eloquent builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Default order by option
     *
     * @var array
     */
    protected $defaultOrderBy = 'latest';

    /**
     * Create a new QuestionFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        // Default sort by latest
        if (! $this->request->has('order_by')) {
            $this->order_by($this->defaultOrderBy);
        }

        return $this->builder;
    }

    /**
     * Order by function
     *
     * @param string $value - sort type
     * @return string
     */
    public function order_by($value)
    {
        $this->builder->getQuery()->orders = null;

        switch ($value) {
            case 'latest':
                $this->builder->latest();
                break;
            case 'title_asc':
                $this->builder->orderBy("title", "asc");
                break;
            case 'title_desc':
                $this->builder->orderBy("title", "desc");
                break;
            case 'name_asc':
                $this->builder->orderBy("name", "asc");
                break;
            case 'name_desc':
                $this->builder->orderBy("name", "desc");
                break;
            case 'points':
                $this->builder->orderBy("points", "desc");
                break;
            case 'most_rated':
                $this->builder->orderBy("votes_counted", "desc");
                break;
            case 'most_popular':
                $this->builder->orderBy("favorites_counted", "desc");
                break;
            case 'most_followed':
                $this->builder->orderBy("followers_counted", "desc");
                break;
            case 'most_viewed':
                $this->builder->orderBy("views", "desc");
                break;
            case 'oldest':
                $this->builder->oldest();
                break;
            case 'trending':
                $this->builder->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
                    ->orderBy("votes_counted", "desc");
                break;
            case 'interesting':
                $this->builder->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
                    ->orderBy("views", "desc");
                break;
            default:
                $this->builder->latest();
                break;
        }
    }

    /**
     * Filter by user id
     *
     * @param int $userId
     * @return string
     */
    public function added_by(int $userId)
    {
        $this->builder->where('user_id', $userId);
    }

    /**
     * Followed by user id
     *
     * @param int $userId
     * @return string
     */
    public function followed_by(int $userId)
    {
        $this->builder->whereHas('followers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }

    /**
     * Exclude by ID
     *
     * @param int $id
     * @return string
     */
    public function exclude(int $id)
    {
        $this->builder->where('id', '!=', $id);
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    public function getFilters()
    {
        return array_filter($this->request->only($this->filters));
    }
}
