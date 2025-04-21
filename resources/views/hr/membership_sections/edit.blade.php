@extends('layouts.master')

@section('title') @lang('translation.EditMembershipSection') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.EditMembershipSection') @endslot
@endcomponent

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Edit Membership Section Form -->
            <form action="{{ route('hr.membership-sections.update', $membershipSection->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h4>@lang('translation.MembershipSectionDetails')</h4>

                <div class="row">
                    <!-- Membership Section Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name">@lang('translation.Name')</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $membershipSection->name) }}">
                    </div>
                </div>

                <div class="row">
                    <!-- Membership Section Description -->
                    <div class="col-md-12 mb-3">
                        <label for="description">@lang('translation.Description')</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $membershipSection->description) }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-block">@lang('translation.Update')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
