<?php

namespace App\Http\Controllers;

use App\Topic;
use App\User;
use App\Question;
use App\Page;

class PageController extends Controller
{
    /**
     * Create a new PageController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['notifications']);
    }

    /**
     * Display index feed page
     */
    public function home()
    {
        $method = 'feed';
        $filter = (request('only')) ? '?only='.request('only') : '';

        return view('questions.index', compact('filter', 'method'));
    }

    /**
     * Display questions page
     */
    public function questions()
    {
        $method = 'get';
        $filter = (request('only')) ? '?only='.request('only') : '';

        return view('questions.index', compact('filter', 'method'));
    }

    /**
     * Display the question page
     *
     * @param   Question  $question
     * @return  view
     */
    public function question(Question $question)
    {
        $question->increment('views');

        return view('questions.view', compact('question'));
    }

    /**
     * Display users page
     */
    public function users()
    {
        return view('users.index');
    }

    /**
     * Display the user
     *
     * @param   User  $user
     * @return  view
     */
    public function user(User $user)
    {
        $tab = (request('tab')) ? request('tab') : 'about';

        return view('users.view', compact(['user', 'tab']));
    }

    /**
     * Display topics page
     */
    public function topics()
    {
        return view('topics.index');
    }

    /**
     * Display the topic page
     *
     * @param   Topic $topic
     * @return  view
     */
    public function topic(Topic $topic)
    {
        $tab = (request('tab')) ? request('tab') : 'questions';

        if ($topic->is_space) {
            $topic->owner = User::withCount(['questions', 'answers'])
                ->where('id', $topic->user_id)
                ->first();
        }

        return view('topics.view', compact(['topic', 'tab']));
    }

    /**
     * Display spaces page
     */
    public function spaces()
    {
        return view('spaces.index');
    }

    /**
     * Display notifications page
     */
    public function notifications()
    {
        return view('notifications.index');
    }

    /**
     * Display search results page
     *
     * @param   String  $query
     * @return  view
     */
    public function search(String $query)
    {
        return view('search.view', compact(['query']));
    }

    /**
     * Display custom page content
     *
     * @param   Page    $page
     * @return  view
     */
    public function view(Page $page)
    {
        return view('page.view', compact(['page']));
    }

    /**
     * Return pages for footer
     *
     * @return  array with pages
     */
    public function index()
    {
        return Page::where('is_published', '1')->get();
    }
}
