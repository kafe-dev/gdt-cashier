@php use App\Models\Paygate; @endphp
@extends('_layouts.main')

@section('content')

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Danh sách Paygates</h4>
            <p class="card-title-desc">
                Nhấn <a href="{{ route('app.paygate.create') }}" class="text-primary">vào đây</a> để thêm Paygate mới.
            </p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="fw-bold">#</th>
                        <th class="fw-bold">Tên</th>
                        <th class="fw-bold">URL</th>
                        <th class="fw-bold">Loại</th>
                        <th class="fw-bold">Trạng thái</th>
                        <th class="fw-bold">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($disputes as $index => $dispute)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $paygate->name }}</td>
                            <td>{{ $paygate->url }}</td>
                            <td>{{ Paygate::TYPE[$paygate->type] }}</td>
                            <td>{{ Paygate::STATUS[$paygate->status] }}</td>
                            <td>
                                <a href="{{ route('app.paygate.show', $paygate->id) }}" class="btn btn-light btn-sm">Chi tiết</a>
                                <a href="{{ route('app.paygate.update', $paygate->id) }}" class="btn btn-light btn-sm">Sửa</a>
                                @if ($paygate->status == Paygate::STAUTS_ACTIVE)
                                    <a href="{{ route('app.paygate.block', $paygate->id) }}"
                                       class="btn btn-light btn-sm">Block</a>
                                @else
                                    <a href="{{ route('app.paygate.unblock', $paygate->id) }}"
                                       class="btn btn-light btn-sm">Unblock</a>
                                @endif
                                <form action="{{ route('app.paygate.delete', $paygate->id) }}" method="POST"
                                      style="display:inline;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-light btn-sm">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end card body -->
    </div>
@endsection
