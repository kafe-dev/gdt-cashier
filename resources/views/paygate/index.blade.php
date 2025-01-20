@extends('_layouts.main')

@section('content')
    <h1>Danh sách Paygates</h1>
    <a href="{{ route('app.paygate.create') }}">Thêm mới Paygate</a>

    <table>
        <thead>
        <tr>
            <th>Tên</th>
            <th>URL</th>
            <th>Loại</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @foreach($paygates as $paygate)
            <tr>
                <td>{{ $paygate->name }}</td>
                <td>{{ $paygate->url }}</td>
                <td>{{ $paygate->type }}</td>
                <td>{{ $paygate->status }}</td>
                <td>
                    <a href="{{ route('app.paygate.update', $paygate->id) }}">Sửa</a>
                    <form action="{{ route('app.paygate.delete', $paygate->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('POST')
                        <button type="submit">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
