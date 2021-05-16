<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;

class ImageController extends Controller
{
    /**
     * Create a new ImageController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new image
     *
     * @param   ImageRequest $request
     * @return  json with response
     */
    public function store(ImageRequest $request)
    {
        $image = $request->file('upload')->store('images', 'public');

        return response()->json([
            'fileName' => basename($image),
            'uploaded' => 1,
            'url' => '/storage/images/'.basename($image),
        ]);
    }
}
