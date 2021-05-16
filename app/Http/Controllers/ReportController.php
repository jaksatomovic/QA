<?php

namespace App\Http\Controllers;

use App\Report;
use App\Http\Requests\ReportRequest;

class ReportController extends Controller
{
    /**
     * Create a new ReportController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new report
     *
     * @param   ReportRequest $request
     * @return  json with message
     */
    public function store(ReportRequest $request)
    {
        $message = __('Report was sent').'!';

        $report = Report::where('user_id', auth()->id())
                        ->where('report_type', $request->report_type)
                        ->where('report_id', $request->report_id)
                        ->first();

        if ($report) {
            $message = __('Report already sent').'!';
        } else {
            $request->user()
                    ->reports()
                    ->create($request->only('report_type', 'report_id', 'message'));
        }

        return response()->json([
            'message' => $message,
        ]);
    }
}
