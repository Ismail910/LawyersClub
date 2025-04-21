@extends('layouts.master')

@section('title') @lang('translation.ArchivedBudgetDetails') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.ArchivedBudgets') @endslot
    @slot('title') @lang('translation.ArchivedBudgetDetails') @endslot
@endcomponent

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">@lang('translation.ArchivedBudgetDetails')</h4>
                </div>
                <div class="card-body">
                    <!-- Archived Budget Information -->
                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.OriginalBudget'):</h5>
                        <p class="fw-bold text-dark">
                            {{ $archivedBudget->originalBudget ? 'Budget ID: ' . $archivedBudget->originalBudget->id : __('translation.not_found') }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Category'):</h5>
                        <p class="fw-bold text-dark">{{ $archivedBudget->category->name ?? __('translation.not_found') }}</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Amount'):</h5>
                        <p class="fw-bold text-dark">{{ number_format($archivedBudget->amount, 2) }} @lang('translation.Currency')</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Notes'):</h5>
                        <p class="text-dark">{{ $archivedBudget->notes ?? __('translation.not_found') }}</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.ArchivedAt'):</h5>
                        <p class="text-dark">{{ $archivedBudget->archived_at }}</p>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('archived-budgets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> @lang('translation.BackToArchivedBudgets')
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
