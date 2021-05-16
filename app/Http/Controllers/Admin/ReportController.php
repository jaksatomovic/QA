<?php

namespace App\Http\Controllers\Admin;

use App\Report;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Input;
use App\Http\Filters\ReportFilters;

class ReportController extends AdminController
{
    /**
     * Return counted per day reports for last 7 days
     *
     * @return array with results
     */
    public function countPerDay()
    {
        return $this->countGroupedByDay(Report::class);
    }

    /**
     * Counting number of reports for selected dates
     *
     * @return integer - reports number
     */
    public function countPerRange()
    {
        return $this->countPerDateRange(Report::class, request()->from, request()->to);
    }

    /**
     * Return filtered reports
     *
     * @param   ReportFilters $filters
     * @return  array with reports
     */
    public function index(ReportFilters $filters)
    {
        $limit = (request()->has('limit')) ? request()->get('limit') : 15;

        $reports = Report::with('user')
            ->filter($filters)
            ->paginate($limit)
            ->appends(Input::except('page'));

        return response()->json($this->buildPagination($reports));
    }

    /**
     * Counting number of unsolved reports
     *
     * @return integer - unsolved reports number
     */
    public function countUnsolved()
    {
        return Report::where('is_solved', '0')->count();
    }

    /**
     * Mark all reports as solved
     *
     * @return  json
     */
    public function solveAll()
    {
        Report::where('is_solved', '0')
            ->update(['is_solved' => '1']);

        return response()->json([
            'solved' => 'all',
        ]);
    }

    /**
     * Mark report as solved
     *
     * @param   Report  $report
     * @return  json
     */
    public function solve(Report $report)
    {
        $report->update(['is_solved' => '1']);

        return response()->json([
            'solved' => $report->id,
        ]);
    }

    /**
     * Delete a report
     *
     * @param   Report  $report
     * @return  json
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return response()->json([
            'deleted' => $report->id,
        ]);
    }
}
