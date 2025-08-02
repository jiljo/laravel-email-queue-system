<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Time</th>
            @if($status === 'failed')
                <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($emails as $email)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $email->email }}</td>
                <td>{{ $email->subject }}</td>
                <td>{{ ucfirst($email->status) }}</td>
                <td>{{ $email->created_at->diffForHumans() }}</td>
                @if($status === 'failed')
                    <td>
                        <button class="btn btn-sm btn-warning retry-btn" data-id="{{ $email->id }}">Retry</button>
                    </td>
                @endif
            </tr>
        @empty
            <tr><td colspan="{{ $status === 'failed' ? 6 : 5 }}">No emails found.</td></tr>
        @endforelse
    </tbody>
</table>
