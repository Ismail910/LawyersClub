@extends('layouts.master')

@section('title') @lang('translation.EditMember') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.EditMember') @endslot
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

            <!-- Edit Member Form -->
            <form action="{{ route('hr.members.update', $member->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h4>@lang('translation.MemberDetails')</h4>

                <div class="row">
                    <!-- Member Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name">@lang('translation.Name')</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $member->name) }}" >
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6 mb-3">
                        <label for="phone">@lang('translation.Phone')</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="amount">@lang('translation.Amount')</label>
                        <input type="string" name="amount" id="amount" class="form-control" value="{{ old('amount', $member->amount) }}" placeholder="@lang('translation.EnterAmount')" min="0" step="0.01" >
                    </div>
                </div>

                <div class="row">
                    <!-- Job Title -->
                    <div class="col-md-6 mb-3">
                        <label for="job_title">@lang('translation.JobTitle')</label>
                        <input type="text" name="job_title" id="job_title" class="form-control" value="{{ old('job_title', $member->job_title) }}" >
                    </div>

                    <!-- Employee Code -->
                    <div class="col-md-6 mb-3">
                        <label for="employee_code">@lang('translation.EmployeeCode')</label>
                        <input type="text" name="employee_code" id="employee_code" class="form-control" value="{{ old('employee_code', $member->employee_code) }}" >
                    </div>
                </div>


                <div class="row">
                    <!-- Membership Number -->
                    <div class="col-md-6 mb-3">
                        <label for="membership_number">@lang('translation.MembershipNumber')</label>
                        <input type="text" name="membership_number" id="membership_number" class="form-control" value="{{ old('membership_number', $member->membership_number) }}">
                    </div>

                    <!-- Membership Date -->
                    <div class="col-md-6 mb-3">
                        <label for="membership_date">@lang('translation.MembershipDate')</label>
                        <input type="date" name="membership_date" id="membership_date" class="form-control" value="{{ old('membership_date', $member->membership_date) }}">
                    </div>
                </div>

                <div class="row">
                    <!-- Address -->
                    <div class="col-md-6 mb-3">
                        <label for="address">@lang('translation.Address')</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $member->address) }}">
                    </div>

                    <!-- Payment Voucher Number -->
                    <div class="col-md-6 mb-3">
                        <label for="payment_voucher_number">@lang('translation.PaymentVoucherNumber')</label>
                        <input type="text" name="payment_voucher_number" id="payment_voucher_number" class="form-control" value="{{ old('payment_voucher_number', $member->payment_voucher_number) }}">
                    </div>

                </div>

                <div class="row">
                    <!-- Last Payment Year -->
                    <div class="col-md-6 mb-3">
                        <label for="last_payment_year">@lang('translation.LastPaymentYear')</label>
                        <input type="text" name="last_payment_year" id="last_payment_year" class="form-control" value="{{ old('last_payment_year', $member->last_payment_year) }}">
                    </div>

                    <!-- Printing Status -->
                    <div class="col-md-6 mb-3">
                        <label for="printing_status">@lang('translation.PrintingStatus')</label>
                        <input type="text" name="printing_status" id="printing_status" class="form-control" value="{{ old('printing_status', $member->printing_status) }}">
                    </div>
                </div>

                <div class="row">
                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label for="notes">@lang('translation.Notes')</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $member->notes) }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <!-- Printing and Payment Date -->
                    <div class="col-md-6 mb-3">
                        <label for="printing_and_payment_date">@lang('translation.PrintingAndPaymentDate')</label>
                        <input type="date" name="printing_and_payment_date" id="printing_and_payment_date" class="form-control" value="{{ old('printing_and_payment_date', $member->printing_and_payment_date) }}">
                    </div>

                    <!-- Payment Date -->
                    <div class="col-md-6 mb-3">
                        <label for="payment_date">@lang('translation.PaymentDate')</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date', $member->payment_date) }}">
                    </div>
                </div>

                <div class="row">
                    <!-- Current Year Paid -->
                    <div class="col-md-6 mb-3">
                        <label for="current_year_paid">@lang('translation.CurrentYearPaid')</label>
                        <input type="text" name="current_year_paid" id="current_year_paid" class="form-control" value="{{ old('current_year_paid', $member->current_year_paid) }}">
                    </div>

                    <!-- Voting Right -->
                    <div class="col-md-6 mb-3">
                        <label for="voting_right">@lang('translation.VotingRight')</label>
                        <input type="text" name="voting_right" id="voting_right" class="form-control" value="{{ old('voting_right', $member->voting_right) }}">
                    </div>
                </div>

                <div class="row">
                    <!-- Gender -->
                    <div class="col-md-6 mb-3">
                        <label for="gender">@lang('translation.Gender')</label>
                        <input type="text" name="gender" id="gender" class="form-control" value="{{ old('gender', $member->gender) }}">
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
