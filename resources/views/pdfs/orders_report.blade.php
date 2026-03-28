<!DOCTYPE html>
<html @if (app()->getLocale() == 'ar') dir="rtl" @else dir="ltr" @endif>

<head>
    <meta charset="utf-8">
    <title>{{ __('pdf.daily_orders_report') }} - {{ $date->toDateString() }}</title>
    <link href="{{ URL::asset('assets/css/pdf.css') }}" rel="stylesheet">
    @if (app()->getLocale() == 'ar')
        <link href="{{ URL::asset('assets/css-rtl/pdf.css') }}" rel="stylesheet">
    @endif
</head>

<body>
    <div class="header">
        <h1>{{ __('pdf.daily_orders_report') }}</h1>
        <h3>{{ __('pdf.date') }}: {{ $date->toDateString() }}</h3>
        <p>{{ __('pdf.total_orders') }}: {{ $total_orders }}</p>
    </div>

    <!-- Order Statistics -->
    <div class="stats">
        <h2>{{ __('pdf.order_statistics') }}</h2>
        @foreach ($counts as $status => $count)
            <div class="stat-card">
                <strong>{{ $status }}:</strong> {{ $count }} {{ __('pdf.orders') }}
            </div>
        @endforeach
    </div>

    <!-- Orders by Status -->
    @foreach ($grouped as $status => $orders)
        @if ($orders->count() > 0)
            <div class="section">
                <div class="section-title">
                    {{ $statuses[$status] }} {{ __('pdf.orders') }} ({{ $orders->count() }})
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('pdf.order_id') }}</th>
                            <th>{{ __('pdf.client') }}</th>
                            <th>{{ __('pdf.worker') }}</th>
                            <th>{{ __('pdf.amount') }}</th>
                            <th>{{ __('pdf.created_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->client_name }}</td>
                                <td>{{ $order->worker_name ?? __('pdf.n_a') }}</td>
                                <td>
                                    @if ($order->total)
                                        ${{ number_format((float) $order->total, 2, '.', '') }}
                                    @else
                                        {{ __('pdf.n_a') }}
                                    @endif
                                </td>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach

    @if ($total_orders === 0)
        <div style="text-align: center; padding: 40px;">
            <h3>{{ __('pdf.no_orders_found') }} {{ $date->toDateString() }}</h3>
        </div>
    @endif
</body>

</html>
