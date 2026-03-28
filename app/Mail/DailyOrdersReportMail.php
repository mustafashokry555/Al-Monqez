<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyOrdersReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $pdfUrl;
    public $date;
    public $counts;

    /**
     * Create a new message instance.
     */
    public function __construct(string $pdfUrl, $date, $counts)
    {
        $this->pdfUrl = $pdfUrl;
        $this->date = $date;
        $this->counts = $counts;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails.daily_orders_report_subject') . ' - ' . $this->date->toDateString(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reports.daily_orders',
            with: [
                'date' => $this->date,
                'counts' => $this->counts,
                'pdfUrl' => $this->pdfUrl,
                'isRTL' => 1,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Extract file path from URL to locate it inside public/uploads
        $relativePath = str_replace(url('/') . '/uploads/', '', $this->pdfUrl);
        $fullPath = public_path('uploads/' . $relativePath);

        $fileName = __('emails.daily_orders_file_name') . '_' . $this->date->format('Y_m_d') . '.pdf';

        return [
            Attachment::fromPath($fullPath)
                ->as($fileName)
                ->withMime('application/pdf'),
        ];
    }
}
