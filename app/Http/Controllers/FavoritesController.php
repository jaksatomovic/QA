<?php

namespace App\Http\Controllers;

use App\Question;

class FavoritesController extends Controller
{
    /**
     * Create a new FavoritesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Add question into favorites
     *
     * @param   Question $question
     * @return  json - number of users who added question in favorites
     */
    public function store(Question $question)
    {
        $question->favorites()->attach(auth()->id());

        $favoritesCounted = $question->updateFavoritesCounter($question);

        return response()->json([
            'favorites' => $favoritesCounted,
        ]);
    }

    /**
     * Remove question from favorites
     *
     * @param   Question $question
     * @return  json - number of users who added question in favorites
     */
    public function destroy(Question $question)
    {
        $question->favorites()->detach(auth()->id());

        $favoritesCounted = $question->updateFavoritesCounter($question);

        return response()->json([
            'favorites' => $favoritesCounted,
        ]);
    }
}
