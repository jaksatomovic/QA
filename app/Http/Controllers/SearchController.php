<?php

namespace App\Http\Controllers;

use App\Question;
use App\Topic;
use App\User;

class SearchController extends Controller
{
    /**
     * Return search results
     *
     * @param   string  $search
     * @return  array with results
     */
    public function index(String $search)
    {
        $results = [];
        $limit = (request()->has('limit')) ? request()->get('limit') : config('services.pagination.search');

        if (request()->has('all')) {
            if ($search) {
                $results = array_merge($results, Topic::search($search)
                    ->where('is_approved', 1)->paginate($limit)->items());
                $results = array_merge($results, User::search($search)
                    ->where('status', 1)->paginate($limit)->items());
                $results = array_merge($results, Question::search($search)
                    ->paginate($limit)->items());
            }
        }

        return $results;
    }
}
