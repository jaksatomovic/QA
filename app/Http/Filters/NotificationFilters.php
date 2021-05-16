<?php

namespace App\Http\Filters;

use App\Http\Filters\Filters;

class NotificationFilters extends Filters
{
    /**
     * Default order by value for answers
     */
    protected $defaultOrderBy = 'latest';

    /**
     * Available filters for notifications
     */
    protected $filters = [
        'order_by'
    ];
}
