@extends('layouts.master')

@section('title') @lang('translation.Subscription_Details') @endsection

@section('content')

@component('components.breadcrumb')
@slot('li_1') @lang('translation.Dashboards') @endslot
@slot('title') @lang('translation.Subscription_Details') @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="bg-primary-subtle">
                <div class="row">
                    <div class="col-9">
                        <div class="text-primary p-4">
                            <h3 class="text-primary">@lang('translation.Welcome_Back'), {{ Str::ucfirst($user->username) }}!</h3>
                            <p>@lang('translation.Your_Subscription_Dashboard')</p>
                        </div>
                    </div>
                    <div class="col-3 align-self-center">
                        <img src="{{ isset($user->img) ? asset($user->img) : URL::asset('build/images/profile-img.png') }}" alt="@lang('translation.User_Image')" class="img-fluid rounded-circle" width="100">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- User Details -->
                <form action="{{ route('subscription.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Avatar -->
                        <div class="col-md-4 mb-3">
                            <label for="avatar">@lang('translation.User_Image')</label>
                            <input type="file" name="avatar" id="avatar" class="form-control">
                        </div>

                        <!-- Email -->
                        <div class="col-md-4 mb-3">
                            <label for="username">@lang('translation.username')</label>
                            <input type="username" name="username" id="username" class="form-control" value="{{ $user->username }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email">@lang('translation.Email')</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-4 mb-3">
                            <label for="phone">@lang('translation.Phone')</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}" required>
                        </div>

                        <!-- Nationality -->
                        <div class="col-md-4 mb-3">
                            <label for="nationality">@lang('translation.Nationality')</label>
                            <input type="text" name="nationality" id="nationality" class="form-control" value="{{ $user->nationality->name ?? 'N/A' }}" readonly>
                        </div>

                        <!-- Balance -->
                        <div class="col-md-4 mb-3">
                            <label for="balance">@lang('translation.PointsBalance')</label>
                            <input type="text" name="balance" id="balance" class="form-control" value="{{ $user->balance }}" readonly>
                        </div>


                        <!-- Identification Status -->
                        <div class="col-md-4 mb-3">
                            <label for="identification_status">@lang('translation.Identification_status')</label>
                            <input type="text" name="identification_status" id="identification_status" class="form-control" value="{{ $user->identification_status }}"readonly>
                        </div>
                    </div>

                    <!-- Service Details -->
                    <h4 class="mt-4">@lang('translation.Services')</h4>
                    <div class="row">
                        @foreach ($categoryServices as $category)
                        <div class="col-md-12 mb-3">
                            <div class="border p-3">
                                <h5 class="text-primary">{{ $category->ar_name }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('translation.Start_Date')</label>
                                        <input type="text" class="form-control" value="{{ $category->start_date }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('translation.End_Date')</label>
                                        <input type="text" class="form-control" value="{{ $category->end_date }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @foreach ($services as $service)
                        <div class="col-md-12 mb-3">
                            <div class="border p-3">
                                <h5 class="text-primary">{{ $service->ar_name }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('translation.Start_Date')</label>
                                        <input type="text" class="form-control" value="{{ $service->start_date }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('translation.End_Date')</label>
                                        <input type="text" class="form-control" value="{{ $service->end_date }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Save Button -->
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">@lang('translation.Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<!-- Include any specific scripts if necessary -->
@endsection
