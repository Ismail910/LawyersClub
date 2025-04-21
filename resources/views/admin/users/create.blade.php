@extends('layouts.master')

@section('title') @lang('translation.AddService') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.AddService') @endslot
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
            <div class="container mt-5">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- User Details -->
                    <h4>@lang('translation.UserDetails')</h4>

                    <div class="row">
                        <!-- Full Name (col-6) -->
                        <div class="col-md-6 mb-3">
                            <label for="fullname">@lang('translation.FullName')</label>
                            <input type="text" name="fullname" id="fullname" class="form-control" value="{{ old('fullname') }}">
                        </div>

                        <!-- Email (col-6) -->
                        <div class="col-md-6 mb-3">
                            <label for="email">@lang('translation.Email')</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Phone with country code (col-6) -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">@lang('translation.phone_number') <span class="text-danger">*</span></label>
                            <!-- Hidden input where the full phone number is stored -->
                            <input type="hidden" id="phone_full" name="phone" value="{{ old('phone') }}">

                            <!-- User-facing phone input field (not submitted directly) -->
                            <input name="phone_display" type="text" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone_display') }}" id="phone" placeholder="@lang('translation.enter_phone')" autocomplete="phone" autofocus>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password (col-6) -->
                        <div class="col-md-6 mb-3">
                            <label for="password">@lang('translation.Password')</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>

                        <!-- Nationality (col-6) -->
                        <div class="col-md-6 mb-3">
                            <label for="nationality">@lang('translation.Nationality')</label>
                            <select name="nationality" id="nationality" class="form-control">
                                @foreach($nationalities as $nationality)
                                    <option value="{{ $nationality->id }}" {{ old('nationality') == $nationality->id ? 'selected' : '' }}>
                                        {{ $nationality->ar_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Profile Image Upload (col-6) -->
                        <div class="col-md-6 mb-3">
                            <label for="img">@lang('translation.ProfileImage')</label>
                            <input type="file" name="img" id="img" class="form-control">
                        </div>

                        <!-- Identification Image Upload (col-6) -->
                        <div class="col-md-6 mb-3">
                            <label for="identification_img">@lang('translation.IdentificationImage')</label>
                            <input type="file" name="identification_img" id="identification_img" class="form-control">
                        </div>
                    </div>

                    <!-- Location Details -->
                    <h4>@lang('translation.LocationDetails')</h4>

                    <!-- Location and Address -->
                    <label for="address"><strong>@lang('translation.Address')</strong></label>
                    <input type="text" name="location" id="address" placeholder="@lang('translation.AddressPlaceholder')" readonly class="form-control mb-3" value="{{ old('location') }}">

                    <fieldset class="gllpLatlonPicker">
                        <div class="gllpMap form-control mb-3" id="map" style="height: 400px;"></div>
                        <input type="hidden" name="lat" class="gllpLatitude" id="lat" value="{{ old('lat') }}">
                        <input type="hidden" name="lng" class="gllpLongitude" id="lng" value="{{ old('lng') }}">
                        <input type="hidden" class="gllpZoom" value="15">
                    </fieldset>

                    <!-- Hidden Inputs to Store Address Components -->
                    <input type="hidden" name="location3" id="map-address3" value="{{ old('location3') }}">
                    <input type="hidden" name="location4" id="map-address4" value="{{ old('location4') }}">
                    <input type="hidden" name="location5" id="map-address5" value="{{ old('location5') }}">
                    <input type="hidden" name="postal" id="map-postal" value="{{ old('postal') }}">
                    <input type="hidden" name="street" id="map-street" value="{{ old('street') }}">
                    <input type="hidden" name="country" id="map-country3" value="{{ old('country') }}">
                    <input type="hidden" name="area" id="map-state3" value="{{ old('area') }}">
                    <input type="hidden" name="city" id="map-city3" value="{{ old('city') }}">

                    <button type="submit" class="btn btn-primary">Submit</button>
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

<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyB1toLB88bKZC4Uy1ljQPty1MQCPf2ZbHE&language=ar"></script>

<script src="{{ asset('assets/map/jquery-1.7.2.min.js') }}"></script>
<script src="{{ asset('assets/map/jquery-gmaps-latlon-picker.js') }}"></script>

<script type="text/javascript">
 $(document).ready(function() {
    // Initialize the map and handle location changes
    $('.gllpLatlonPicker').on('locationChanged', function(event) {
        let lat = $('#lat').val();
        let lng = $('#lng').val();
        updateAddressFields(lat, lng);
    });

    function updateAddressFields(lat, lng) {
        let geocoder = new google.maps.Geocoder();
        let latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };

        geocoder.geocode({ location: latlng }, function(results, status) {
            if (status === 'OK') {
                if (results[0]) {
                    let address = results[0].formatted_address;
                    $('#address').val(address);
                    $('#map-address3').val(address);

                    // Initialize empty values for country, area, and city
                    let country = null, area = null, city = null;

                    results[0].address_components.forEach(component => {
                        if (component.types.includes("country")) {
                            country = component.long_name;
                            $('#map-country3').val(country);
                        }
                        if (component.types.includes("administrative_area_level_1")) {
                            area = component.long_name;
                            $('#map-state3').val(area);
                        }
                        if (component.types.includes("locality") || component.types.includes("administrative_area_level_2")) {
                            city = component.long_name;
                            $('#map-city3').val(city);
                        }
                    });

                    // Log values for debugging
                    console.log("Country:", country);
                    console.log("Area:", area);
                    console.log("City:", city);
                } else {
                    alert('No results found');
                }
            } else {
                alert('Geocoder failed due to: ' + status);
            }
        });
    }
});


$(document).ready(function() {
    // Initialize intl-tel-input plugin on the #phone field
    const phoneInputField = document.querySelector("#phone");
    const iti = window.intlTelInput(phoneInputField, {
        initialCountry: "sa",  // Default country to Saudi Arabia
        separateDialCode: true,  // Show separate dial code visually, but we'll handle full number on submission
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
    });

    // On form submission, set the full phone number in #phone_full input without "+"
    $('form').on('submit', function(e) {
        // Get the full phone number in E.164 format and remove the "+"
        const fullPhoneNumber = iti.getNumber(intlTelInputUtils.numberFormat.E164).replace('+', '');
        $("#phone_full").val(fullPhoneNumber); // Set full number in hidden field for submission
    });
});


</script>
