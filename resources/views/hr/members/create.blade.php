@extends('layouts.master')

@section('title') @lang('translation.AddMember') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.AddMember') @endslot
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

            <!-- Create Member Form -->
            <form action="{{ route('hr.members.store') }}" method="POST">
                @csrf

                <h4>@lang('translation.MemberDetails')</h4>

                <div class="row">
                    <!-- Member Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name">@lang('translation.Name')</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="@lang('translation.EnterName')" >
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6 mb-3">
                        <label for="phone">@lang('translation.Phone')</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="@lang('translation.EnterPhone')" >
                    </div>
                    <!-- Amount -->
                    <div class="col-md-6 mb-3">
                        <label for="amount">@lang('translation.Amount')</label>
                        <input type="string" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" placeholder="@lang('translation.EnterAmount')" min="0" step="0.01">
                    </div>

                </div>

                <div class="row">
                    <!-- Job Title -->
                    <div class="col-md-6 mb-3">
                        <label for="job_title">@lang('translation.JobTitle')</label>
                        <input type="text" name="job_title" id="job_title" class="form-control" value="{{ old('job_title') }}" placeholder="@lang('translation.EnterJobTitle')" >
                    </div>

                    <!-- Employee Code -->
                    <div class="col-md-6 mb-3">
                        <label for="employee_code">@lang('translation.EmployeeCode')</label>
                        <input type="text" name="employee_code" id="employee_code" class="form-control" value="{{ old('employee_code') }}" placeholder="@lang('translation.EnterEmployeeCode')" >
                    </div>
                </div>



                <div class="row">
                    <!-- Membership Number -->
                    <div class="col-md-6 mb-3">
                        <label for="membership_number">@lang('translation.MembershipNumber')</label>
                        <input type="text" name="membership_number" id="membership_number" class="form-control" value="{{ old('membership_number') }}" placeholder="@lang('translation.EnterMembershipNumber')">
                    </div>

                    <!-- Membership Date -->
                    <div class="col-md-6 mb-3">
                        <label for="membership_date">@lang('translation.MembershipDate')</label>
                        <input type="date" name="membership_date" id="membership_date" class="form-control" value="{{ old('membership_date') }}">
                    </div>
                </div>

                <div class="row">
                    <!-- Address -->
                    <div class="col-md-6 mb-3">
                        <label for="address">@lang('translation.Address')</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" placeholder="@lang('translation.EnterAddress')">
                    </div>

                    <!-- Payment Voucher Number -->
                    <div class="col-md-6 mb-3">
                        <label for="payment_voucher_number">@lang('translation.PaymentVoucherNumber')</label>
                        <input type="text" name="payment_voucher_number" id="payment_voucher_number" class="form-control" value="{{ old('payment_voucher_number') }}" placeholder="@lang('translation.EnterPaymentVoucherNumber')">
                    </div>
                </div>

                <div class="row">
                    <!-- Last Payment Year -->
                    <div class="col-md-6 mb-3">
                        <label for="last_payment_year">@lang('translation.LastPaymentYear')</label>
                        <input type="text" name="last_payment_year" id="last_payment_year" class="form-control" value="{{ old('last_payment_year') }}" placeholder="@lang('translation.EnterLastPaymentYear')">
                    </div>

                    <!-- Printing Status -->
                    <div class="col-md-6 mb-3">
                        <label for="printing_status">@lang('translation.PrintingStatus')</label>
                        <input type="text" name="printing_status" id="printing_status" class="form-control" value="{{ old('printing_status') }}" placeholder="@lang('translation.EnterPrintingStatus')">
                    </div>
                </div>

                <div class="row">
                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label for="notes">@lang('translation.Notes')</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="@lang('translation.EnterNotes')">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <!-- Printing and Payment Date -->
                    <div class="col-md-6 mb-3">
                        <label for="printing_and_payment_date">@lang('translation.PrintingAndPaymentDate')</label>
                        <input type="date" name="printing_and_payment_date" id="printing_and_payment_date" class="form-control" value="{{ old('printing_and_payment_date') }}">
                    </div>

                    <!-- Payment Date -->
                    <div class="col-md-6 mb-3">
                        <label for="payment_date">@lang('translation.PaymentDate')</label>
                        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date') }}">
                    </div>
                </div>

                <div class="row">
                    <!-- Current Year Paid -->
                    <div class="col-md-6 mb-3">
                        <label for="current_year_paid">@lang('translation.CurrentYearPaid')</label>
                        <input type="text" name="current_year_paid" id="current_year_paid" class="form-control" value="{{ old('current_year_paid') }}" placeholder="@lang('translation.EnterCurrentYearPaid')">
                    </div>

                    <!-- Voting Right -->
                    <div class="col-md-6 mb-3">
                        <label for="voting_right">@lang('translation.VotingRight')</label>
                        <input type="text" name="voting_right" id="voting_right" class="form-control" value="{{ old('voting_right') }}" placeholder="@lang('translation.EnterVotingRight')">
                    </div>
                </div>

                <div class="row">
                    <!-- Gender -->
                    <div class="col-md-6 mb-3">
                        <label for="gender">@lang('translation.Gender')</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="">@lang('translation.SelectGender')</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>@lang('translation.Male')</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>@lang('translation.Female')</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-block">@lang('translation.Save')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
