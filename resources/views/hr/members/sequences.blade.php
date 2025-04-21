@extends('layouts.master')

@section('title') @lang('translation.Members') @endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') @lang('translation.Dashboard') @endslot
        @slot('title') @lang('translation.Members') @endslot
    @endcomponent

    <div class="container-full">
        <!-- Filter Section -->
        <div class="row mt-4">
            <div class="col-md-3">
                <label>@lang('translation.name')</label>
                <input type="text" id="name" class="form-control" placeholder="@lang('translation.name')">
            </div>

            <div class="col-md-3">
                <label>@lang('translation.membership_date')</label>
                <input type="text" id="membership_date_start" class="form-control" placeholder="@lang('translation.start_date')">
                <input type="text" id="membership_date_end" class="form-control" placeholder="@lang('translation.end_date')">
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('translation.name')</th>
                        <th>@lang('translation.phone')</th>
                        <th>@lang('translation.job_title')</th>
                        <th>@lang('translation.membership_number')</th>
                        <th>@lang('translation.membership_date')</th>
                        <th>@lang('translation.sequence_number')</th>
                        <th>@lang('translation.address')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($membersWithSequences as $member)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->phone }}</td>
                            <td>{{ $member->job_title }}</td>
                            <td>{{ $member->membership_number }}</td>
                            <td>{{ $member->membership_date }}</td>
                            <td>{{ $member->sequence_number }}</td>
                            <td>{{ $member->address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>


@endsection
