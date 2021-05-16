<?php

namespace App\Http\Controllers\Admin;

use App\Page;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Filters\PageFilters;

class PageController extends AdminController
{
    /**
     * Create a new PageController instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display admin panel dashboard
     *
     * @return  view
     */
    public function dashboard()
    {
        return view('admin.index');
    }

    /**
     * Display admin panel reports page
     *
     * @return  view
     */
    public function reports()
    {
        $filter = (request('only')) ? '?only='.request('only') : '';

        return view('admin.reports', compact('filter'));
    }

    /**
     * Display admin panel topics page
     *
     * @return  view
     */
    public function topics()
    {
        $filter = (request('only')) ? '?only='.request('only') : '';

        return view('admin.topics', compact('filter'));
    }

    /**
     * Display admin panel spaces page
     *
     * @return  view
     */
    public function spaces()
    {
        $filter = (request('only')) ? '?only='.request('only') : '';

        return view('admin.spaces', compact('filter'));
    }

    /**
     * Display admin panel users page
     *
     * @return  view
     */
    public function users()
    {
        $filter = (request('only')) ? '?only='.request('only') : '';

        return view('admin.users', compact('filter'));
    }

    /**
     * Display admin panel questions page
     *
     * @return  view
     */
    public function questions()
    {
        $filter = (request('only')) ? '?only='.request('only') : '';

        return view('admin.questions', compact('filter'));
    }

    /**
     * Display admin panel answers page
     *
     * @return  view
     */
    public function answers()
    {
        $filter = (request('only')) ? '?only='.request('only') : '';

        return view('admin.answers', compact('filter'));
    }

    /**
     * Display admin panel pages page
     *
     * @return  view
     */
    public function pages()
    {
        $filter = (request('only')) ? '?only='.request('only') : '';

        return view('admin.pages', compact('filter'));
    }

    /**
     * Display admin panel settings page
     *
     * @return  view
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Display admin panel about page
     *
     * @return  view
     */
    public function about()
    {
        return view('admin.about');
    }

    /**
     * Return filtered pages
     *
     * @param   PageFilters $filters
     * @return  array with pages
     */
    public function index(PageFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : 15;

        $pages = Page::filter($filters)
            ->paginate($limit)
            ->appends(Input::except('page'));

        return response()->json($this->buildPagination($pages));
    }

    /**
     * Update the given page
     *
     * @param   Request $request
     * @param   Page    $page
     * @return  json with response
     */
    public function update(Request $request, Page $page)
    {
        $page->update($request->only('title', 'slug', 'body', 'is_published'));

        return response()->json([
            'message' => __('Page was edited').'!',
        ]);
    }

    /**
     * Add new custom page
     *
     * @return  JSON response
     */
    public function store()
    {
        $page = new Page;
        $page->title = request('title');
        $page->slug = request('slug');
        $page->body = request('body');
        $page->is_published = request('is_published');
        $page->save();

        return response()->json([
            'message' => __('Page created').'!',
        ]);
    }

    /**
     * Delete the given page
     *
     * @param   Page    $page
     * @return  json with response
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return response()->json([
            'message' => __('Page was deleted').'!',
        ]);
    }
}
