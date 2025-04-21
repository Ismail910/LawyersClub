@extends('layouts.master')

@section('title') @lang('translation.EditService') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.EditService') @endslot
@endcomponent

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('service.update', $service->id) }}" method="POST" enctype="multipart/form-data" class="custom-form-style">
                @csrf
                @method('PUT')

                <!-- Category Dropdown -->
                <div class="form-group">
                    <label for="cat">@lang('translation.main_category')</label>
                    <select name="cat" class="form-control" required>

                            <option value="{{ $category->id }}" {{ $service->categorys_services_id == $category->id ? 'selected' : '' }}>
                                {{ $category->ar_name }} / {{ $category->en_name }}
                            </option>

                    </select>
                </div>

                <!-- Arabic Name -->
                <div class="form-group">
                    <label for="ar_name">@lang('translation.arabic_name')</label>
                    <input type="text" name="ar_name" class="form-control" value="{{ old('ar_name', $service->ar_name) }}" required>
                </div>

                <!-- English Name -->
                <div class="form-group">
                    <label for="en_name">@lang('translation.english_name')</label>
                    <input type="text" name="en_name" class="form-control" value="{{ old('en_name', $service->en_name) }}" required>
                </div>

                <div class="form-group">
                    <label for="img">@lang('translation.image') (@lang('translation.optional'))</label>
                    <input type="file" name="img" class="form-control">
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary btn-block">@lang('translation.update_service')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection




<!-- Custom CSS -->
<style>
  /* Form container */
.custom-form-style {
    width: 100%; /* Makes the form cover the entire page */
    margin: 0 auto; /* Centers the form on the page */
    padding: 30px; /* Increased padding around the form */
    border-radius: 15px; /* Rounded corners for the form */
    background-color: #f9f9f9; /* Light background for the form */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Adds a subtle shadow */
}

/* Input fields styling */
.custom-form-style .form-control {
    border: 2px solid #7f8b97; /* Blue border for input fields */
    padding: 12px; /* Adjust padding for consistency */
    font-size: 16px; /* Set text size */
    border-radius: 8px; /* Rounded input fields */
    margin-bottom: 20px; /* Add space between form fields */
}

/* Label styles */
.custom-form-style label {
    font-weight: 600; /* Slightly bolder labels */
    margin-bottom: 8px; /* Space between label and input field */
    color: #333; /* Dark gray label color */
    display: block; /* Ensures labels are above the fields */
}


/* Button styling */
.custom-form-style .btn {
    padding: 12px 20px; /* Adjusted padding for better clickability */
    font-size: 18px; /* Increased font size */
    background-color: #007bff; /* Blue button */
    color: #fff; /* White text color */
    border: none; /* Remove button borders */
    border-radius: 8px; /* Rounded button */
    cursor: pointer; /* Pointer on hover */
    transition: background-color 0.3s ease; /* Smooth transition */
}

/* Button hover effect */
.custom-form-style .btn:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

/* Responsive styling for mobile */
@media (max-width: 768px) {
    .custom-form-style {
        padding: 20px; /* Smaller padding for mobile */
    }

    .custom-form-style .form-control, .custom-form-style .btn {
        width: 100%; /* Full width for mobile */
        margin-bottom: 15px; /* Add space between elements */
    }
}
</style>
<!-- intl-tel-input CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

<!-- jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- intl-tel-input JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
