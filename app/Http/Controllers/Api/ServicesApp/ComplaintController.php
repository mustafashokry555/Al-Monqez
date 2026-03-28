<?php

namespace App\Http\Controllers\Api\ServicesApp;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\ServicesApp\Complains\MakeComplainRequest;
use App\Http\Resources\ServicesApp\ComplaintResource;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $complaints = Complaint::select('complaints.id', 'complaints.message', 'complaints.status', 'complaints.created_at')
            ->join('orders', 'complaints.order_id', '=', 'orders.id')
            ->where([['complaints.order_id', $request->order_id], ['orders.client_id', auth()->id()]])
            ->latest()
            ->get();

        return $this->apiResponse(200, 'complaints', null, ComplaintResource::collection($complaints));
    }

    /*----------------------------------------------------------------------------------------------------*/

    public function make(MakeComplainRequest $request)
    {
        Complaint::create([
            'order_id' => $request->order_id,
            'message' => $request->message
        ]);

        return $this->apiResponse(200, __('messages.make_complaint'));
    }
}
