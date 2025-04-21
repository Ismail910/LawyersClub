@extends('layouts.master')

@section('title') @lang('translation.MembershipSectionDetails') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.MembershipSections') @endslot
    @slot('title') @lang('translation.MembershipSectionDetails') @endslot
@endcomponent

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">@lang('translation.MembershipSectionDetails')</h4>
                </div>
                <div class="card-body">
                    <!-- Membership Section Information -->
                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Name'):</h5>
                        <p class="fw-bold text-dark">{{ $membershipSection->name }}</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Description'):</h5>
                        <p class="text-dark">{{ $membershipSection->description ?? __('translation.not_found') }}</p>
                    </div>

                    <!-- Members Section -->
                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Members')</h5>
                        @if($membershipSection->members->isNotEmpty())
                            <ul class="list-group">
                                @foreach($membershipSection->members as $member)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">{{ $member->name }} ({{ $member->job_title }})</span>
                                        <a href="{{ route('hr.members.show', $member->id) }}" class="btn btn-sm btn-info">@lang('translation.View')</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-danger">@lang('translation.not_found')</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('hr.membership-sections.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> @lang('translation.BackToMembershipSections')
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
