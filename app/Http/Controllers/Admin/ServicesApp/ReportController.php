<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->paginate(10);

        return view('admin.services-app.reports.index', compact('reports'));
    }
}
