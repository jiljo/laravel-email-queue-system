<!DOCTYPE html>
<html>
<head>
    <title>Email Queue Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .tab-pane { margin-top: 20px; }
        .table td, .table th { vertical-align: middle; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center"> Email Queue System </h2>

    <!--Email Sending Form -->
    <form id="emailForm" method="POST" action="{{ route('send-email') }}">
        @csrf
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="email" name="email" class="form-control" placeholder="Recipient Email" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="subject" class="form-control" placeholder="Subject" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="message" class="form-control" placeholder="Message" required>
            </div>
        </div>
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary btn-sm">Send Email</button>
        </div>
    </form>

    <!--Tabs to show emails -->
    <ul class="nav nav-tabs mt-5" id="emailTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#queued" role="tab">Queued</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#sent" role="tab">Sent</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#failed" role="tab">Failed</a>
        </li>
    </ul>

    <div class="tab-content" id="emailTabContent">
        <div class="tab-pane fade show active" id="queued" role="tabpanel"></div>
        <div class="tab-pane fade" id="sent" role="tabpanel"></div>
        <div class="tab-pane fade" id="failed" role="tabpanel"></div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // CSRF Token Setup for all AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Form Submission
    $('#emailForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);

        $.post(form.attr('action'), form.serialize())
            .done(function() {
                alert('Email queued successfully!');
                form.trigger('reset');
                loadEmailData();
            })
            .fail(function() {
                alert('Error occurred while queuing the email.');
            });
    });

    // Load Email Data via AJAX
    function loadEmailData() {
        $.get('{{ route("status") }}', function(response) {
            $('#queued').html(response.queued);
            $('#sent').html(response.sent);
            $('#failed').html(response.failed);
        });
    }

    // Retry Failed Email
    $(document).on('click', '.retry-btn', function() {
        const id = $(this).data('id');
        $.post('{{ route("retry") }}', { id: id }, function() {
            alert('Retry queued.');
            loadEmailData();
        });
    });

    // Auto Refresh Every 10 seconds
    setInterval(loadEmailData, 10000);
    $(document).ready(loadEmailData);
</script>
</body>
</html>
