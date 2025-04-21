@extends('layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') @lang('translation.Dashboards') @endslot
        @slot('title') @lang('translation.EmployeeDetails') @endslot
    @endcomponent

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">@lang('translation.EmployeeDetails')</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">@lang('translation.FullName')</label>
                        <p class="form-control-static">{{ $employee->full_name }}</p>
                    </div>
                    <div class="mb-3">
                        <label for="user_name" class="form-label">@lang('translation.Username')</label>
                        <p class="form-control-static">{{ $employee->user_name }}</p>
                    </div>
                   
                    <div class="mb-3">
                        <label for="avatar" class="form-label">@lang('translation.Avatar')</label>
                        @if($employee->avatar)
                            <img src="{{ asset($employee->avatar) }}" alt="{{ $employee->user_name }}" class="img-thumbnail" style="width: 250px; height: 250px;">
                        @else
                            <p class="form-control-static">@lang('translation.NoAvatarAvailable')</p>
                        @endif
                    </div>
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">@lang('translation.EditEmployee')</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
    <style>
        .card-header {
            background-color: #007bff;
            color: #fff;
        }
        .form-control-static {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
    </style>
@endsection
