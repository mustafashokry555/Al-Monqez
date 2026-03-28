<?php

namespace App\Console\Commands;

use App\Mail\DailyOrdersReportMail;
use App\Models\Order;
use App\Models\Report;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Str;

class GenerateDailyOrderReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:orders-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily PDF report for orders and email it to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $statuses = collect(range(0, 4))
            ->mapWithKeys(fn($status) => [$status => __("admin.status_{$status}")])
            ->toArray();

        // Fetch all orders of today with client and worker names
        $orders = Order::select(
            'orders.id',
            'orders.status',
            'orders.total',
            'orders.created_at',
            'clients.name as client_name',
            'workers.name as worker_name'
        )
            ->join('users as clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('users as workers', 'orders.worker_id', '=', 'workers.id')
            ->whereDate('orders.created_at', $today)
            ->get();

        // Group orders by status
        $grouped = $orders->groupBy('status');

        // Count per status - safely handle missing statuses
        $counts = [];
        foreach ($statuses as $key => $label) {
            $counts[$label] = isset($grouped[$key]) ? $grouped[$key]->count() : 0;
        }

        // Prepare data for PDF
        $pdfData = [
            'statuses' => $statuses,
            'grouped' => $grouped,
            'counts' => $counts,
            'date' => $today,
            'total_orders' => $orders->count(),
        ];

        // Generate PDF using PDF facade
        $pdf = PDF::loadView('pdfs.orders_report', $pdfData);
        $pdfContent = $pdf->output();

        // Generate hashed file name (40 characters)
        $hashedName = Str::random(40) . '.pdf';
        $relativePath = 'reports/' . $hashedName;
        $disk = Storage::disk('uploads');

        // Ensure directory exists
        if (!$disk->exists('reports')) {
            $disk->makeDirectory('reports');
        }

        // Save the file with hashed name
        $disk->put($relativePath, $pdfContent);

        // Store in DB with original display name and hashed file name
        Report::create([
            'file_name' => __('pdf.daily_orders_report') . " ({$today->toDateString()})",
            'file_url' => $relativePath,
        ]);

        // Send the email
        $recipient = Setting::select('email')->first()->email ?? '';
        $pdfUrl = url('uploads/' . $relativePath); // Full URL for email

        Mail::to($recipient)->send(new DailyOrdersReportMail($pdfUrl, $today, $counts));
    }
}
