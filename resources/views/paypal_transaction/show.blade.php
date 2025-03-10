@php use Carbon\Carbon; @endphp
@php use App\Utils\ActionWidget; @endphp
@extends('_layouts.main')

@push('page')
    View Paypal Transaction ID: {{ $paypalTransaction->id }}
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('show-paypal-transaction', $paypalTransaction) }}
@endpush

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Paypal Transaction ID: {{ $paypalTransaction->id }}</h4>
                    <p class="text-muted mb-0">This table below is showing the detail information of paypal transaction
                        ID: {{ $paypalTransaction->id }}</p>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($paypalTransaction->toArray() as $key => $value)
                            @if(\Illuminate\Support\Str::endsWith($key, '_at') || $key === 'datetime')
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted">{{ \Illuminate\Support\Str::of($key)->replace('_', ' ')->title() }}:</span>
                                    </div>
                                    {!!
                                        $datetime = null;
                                        if (! empty($value)) $datetime = Carbon::parse($value)
                                    !!}
                                    {!! ! empty($datetime) ? '<span class="x-has-time-converter">'.$datetime->format(config('app.date_format')).'</span>' : '-' !!}
                                </li>
                            @else
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted">{{ \Illuminate\Support\Str::of($key)->replace('_', ' ')->title() }}:</span>
                                    </div>
                                    <span>{{ $value }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer">
                    {{ ActionWidget::renderGoBackBtn('Go Back', 'btn btn-primary') }}
                </div>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
@endsection
