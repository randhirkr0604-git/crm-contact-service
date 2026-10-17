
@extends('common.app')

@section('title', 'Create Lead')

@section('content')

<div class="card main-card shadow-sm">
    <div class="card-body p-4">

        <h3 class="mb-2">Create Lead</h3>
        <p class="text-muted mb-4">
            Create a new lead and automatically generate its CRM contact.
        </p>

        <form class="ajax-form"
              action="{{ route('leads.store') }}"
              method="POST"
              novalidate>

            @csrf

            <div class="mb-3">
                <label class="form-label">Lead Name *</label>
                <input type="text"
                       name="lead_name"
                       class="form-control"
                       placeholder="XYZ Solutions"
                       maxlength="255"
                       required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Name *</label>
                <input type="text"
                       name="contact_name"
                       class="form-control"
                       placeholder="Enter contact name"
                       maxlength="255"
                       required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address *</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="example@email.com"
                       maxlength="255"
                       required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-4">
                <label class="form-label">Phone Number</label>
                <input type="tel"
                       name="phone"
                       class="form-control"
                       placeholder="+91 9876543210"
                       maxlength="20"
                       pattern="[0-9+\-\s()]{7,20}">
                <div class="invalid-feedback">
                    Enter a valid phone number.
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100">
                Create Lead
            </button>

        </form>

    </div>
</div>

@endsection
