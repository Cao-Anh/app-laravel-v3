@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">
            Thông tin hoạt động của {{ optional($logs->first()->user)->username ?? 'Không rõ' }}
        </h1>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>URL</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $index => $log)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->url }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $log->user_agent }}
                        </td>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $logs->links() }}
    </div>
@endsection
