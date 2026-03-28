<!DOCTYPE html>
<html lang="{{ $isRTL ? 'ar' : 'en' }}" dir="{{ $isRTL ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .email-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .rtl-content {
            direction: rtl;
            text-align: right;
        }

        .ltr-content {
            direction: ltr;
            text-align: left;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        ul {
            padding-left: 20px;
        }

        .rtl-content ul {
            padding-right: 20px;
            padding-left: 0;
        }

        li {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <div class="email-container {{ $isRTL ? 'rtl-content' : 'ltr-content' }}">
        <h1>{{ __('emails.greeting') }}</h1>

        <p><strong>{{ __('emails.date') }}:</strong> {{ $date->toDateString() }}</p>

        <h2>{{ __('emails.order_statistics') }}</h2>

        <ul>
            @foreach ($counts as $status => $count)
                <li><strong>{{ __('emails.orders') }} {{ $status }}:</strong> {{ $count }}</li>
            @endforeach
        </ul>

        <hr>

        <p>{{ __('emails.download_link') }}</p>

        <a href="{{ $pdfUrl }}" class="button">
            {{ __('emails.download_button') }}
        </a>

        <div class="signature">
            <p>{{ __('emails.thanks') }}<br>
                <strong>{{ env('MAIL_FROM_NAME') }}</strong>
            </p>
        </div>
    </div>
</body>

</html>
