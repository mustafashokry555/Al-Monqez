<?php

namespace App\Http\Controllers\Admin\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesApp\Complaints\ProcessComplaintRequest;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::select('id', 'order_id', 'message', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.services-app.complaints.index', compact('complaints'));
    }

    /*-----------------------------------------------------------------------------------------------*/

    public function process(ProcessComplaintRequest $request)
    {
        $complaint = Complaint::findOrFail($request->complaint_id);
        $complaint->update(['status' => $request->status]);

        session()->flash('success', __('messages.change_complaint_status'));
        return redirect()->back();
    }
}
