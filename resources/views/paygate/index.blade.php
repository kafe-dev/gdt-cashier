@php use App\Models\Paygate; @endphp
@extends('_layouts.main')

@push('page')
    Manage Paygates
@endpush

@push('breadcrumbs')
    {{ Breadcrumbs::render('manage-paygate') }}
@endpush
@php
    /* @var \App\Models\Paygate $paygate */
@endphp
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Paygate List</h4>
                        <p class="text-muted mb-0">
                            This is a list of all paygate in the system.
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('app.paygate.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3 my-1">
                                <select name="type" class="form-select" aria-label="Default select example">
                                    <option value="" selected><i class="mdi mdi-chevron-down"></i> All</option>
                                    <option value="0" {{ request('type') == '0' ? 'selected' : '' }}>
                                        <i class="mdi mdi-chevron-down"></i> Paypal
                                    </option>
                                    <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>
                                        <i class="mdi mdi-chevron-down"></i> Stripe
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 my-1">
                                <select name="status" class="form-select" aria-label="Default select example">
                                    <option value="" selected><i class="mdi mdi-chevron-down"></i> All</option>
                                    <option value="1" {{ request('status') == Paygate::STATUS_ACTIVE ? 'selected' : '' }}>
                                        <i class="mdi mdi-chevron-down"></i> {{Paygate::STATUS[Paygate::STATUS_ACTIVE]}}
                                    </option>
                                    <option value="0" {{ request('status') == Paygate::STATUS_INACTIVE ? 'selected' : '' }}>
                                        <i class="mdi mdi-chevron-down"></i>{{Paygate::STATUS[Paygate::STATUS_INACTIVE]}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 my-1">
                                <select name="mode" class="form-select" aria-label="Default select example">
                                    <option value="" selected><i class="mdi mdi-chevron-down"></i> All</option>
                                    <option value="1" {{ request('mode') == '1' ? 'selected' : '' }}>
                                        <i class="mdi mdi-chevron-down"></i> Sandbox
                                    </option>
                                    <option value="0" {{ request('mode') == '0' ? 'selected' : '' }}>
                                        <i class="mdi mdi-chevron-down"></i> Production
                                    </option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2"><i class="mdi mdi-magnify"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('app.paygate.index') }}" class="btn btn-danger mt-2">
                            <i class="mdi mdi-filter-remove"></i> Bỏ lọc
                        </a>
                    </form>
                    <table class="table table-bordered">
                        <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Url</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Limitation</th>
                            <th>Mode</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($paygates as $paygate)
                            <tr>
                                <td>{{ $paygate->id }}</td>
                                <td>{{ $paygate->name }}</td>
                                <td>{{ $paygate->url }}</td>
                                <td>{{ Paygate::TYPE[$paygate->type] }}</td>
                                <td>{{ Paygate::STATUS[$paygate->status] }}</td>
                                <td>{{ $paygate->limitation }}</td>
                                <td>{{ Paygate::MODES[$paygate->mode] }}</td>
                                <td>{{ $paygate->created_at }}</td>
                                <td>
                                    @php
                                        $btnView = '<a href="' . route('app.paygate.show', $paygate->id) . '" class="btn btn-sm btn-info m-1" title="View"><i class="fa fa-eye"></i></a>';
                                        $btnUpdate = '<a href="' . route('app.paygate.update', $paygate->id) . '" class="btn btn-sm btn-primary m-1" title="Update"><i class="fa fa-pen"></i></a>';
                                        echo $btnView . ' ' . $btnUpdate;
                                    @endphp
                                    <form action="' . route('app.paygate.delete', $paygate->id) . '" method="POST" style="display:inline;">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-sm btn-danger m-1" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $paygates->onEachSide(1)->links('pagination::bootstrap-5') }} <!-- Hiển thị phân trang -->
                </div>
            </div>
        </div>
    </div>
@endsection
