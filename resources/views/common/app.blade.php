
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CRM Contact Service')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        .main-card {
            max-width: 650px;
            margin: 50px auto;
            border: none;
            border-radius: 12px;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">CRM Contact Service</a>

        <div class="navbar-nav">
            <a class="nav-link" href="{{ route('accounts.create') }}">
                Account
            </a>

            <a class="nav-link" href="{{ route('leads.create') }}">
                Lead
            </a>
        </div>
    </div>
</nav>

<main class="container">
    @yield('content')
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

    $('.ajax-form').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const button = form.find('button[type="submit"]');
        const originalText = button.text();

        // Clear previous validation errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');
        form.find('.alert').remove();

        // Browser frontend validation
        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }

        button.prop('disabled', true).text('Please wait...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),

            success: function (response) {
                form.prepend(`
                    <div class="alert alert-success">
                        ${response.message}
                    </div>
                `);

                form[0].reset();
                form.removeClass('was-validated');
            },

            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    $.each(errors, function (field, messages) {
                        const input = form.find('[name="' + field + '"]');

                        input.addClass('is-invalid');

                        input.siblings('.invalid-feedback')
                             .text(messages[0]);
                    });
                } else {
                    form.prepend(`
                        <div class="alert alert-danger">
                            Something went wrong. Please try again.
                        </div>
                    `);
                }
            },

            complete: function () {
                button.prop('disabled', false).text(originalText);
            }
        });
    });
</script>

@stack('scripts')

</body>
</html>
