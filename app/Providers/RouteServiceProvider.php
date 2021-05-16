<?php

namespace App\Providers;

use App\User;
use App\Answer;
use App\Question;
use App\Topic;
use App\Page;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    public function boot()
    {
        /**
         * Question.show
         */
        Route::bind('question_slug', function ($slug) {
            return Question::with(['user', 'favorited', 'topics'  => function ($query) {
                $query->select('id', 'title', 'slug');
            }])->where('slug', $slug)
            ->first() ?? abort(404);
        });

        /**
         * Topic.show
         */
        Route::bind('topic_slug', function ($slug) {
            return Topic::where('slug', $slug)
                ->with('followed')
                ->withCount('questions')
                ->first() ?? abort(404);
        });

        /**
         * Answer.show
         */
        Route::bind('answer', function ($id) {
            return Answer::with([
                'user', 'question'
            ])->where('id', $id)
            ->first() ?? abort(404);
        });

        /**
         * User.show
         */
        Route::bind('user', function ($id) {
            return User::withCount(['questions', 'answers', 'followTopics', 'followSpaces'])
                ->where('id', $id)
                ->first() ?? abort(404);
        });

        /**
         * Page.show
         */
        Route::bind('page_slug', function ($slug) {
            return Page::where('slug', $slug)
                ->where('is_published', 1)
                ->first() ?? abort(404);
        });

        parent::boot();
    }

    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
