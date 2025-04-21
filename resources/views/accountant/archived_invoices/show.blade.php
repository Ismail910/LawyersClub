@extends('layouts.master')

@section('title') @lang('translation.ArchivedInvoiceDetails') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.ArchivedInvoices') @endslot
    @slot('title') @lang('translation.ArchivedInvoiceDetails') @endslot
@endcomponent

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">@lang('translation.ArchivedInvoiceDetails')</h4>
                </div>
                <div class="card-body">
                    <!-- Archived Invoice Information -->
                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.InvoiceNumber'):</h5>
                        <p class="fw-bold text-dark">{{ $archivedInvoice->invoice_number }}</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Amount'):</h5>
                        <p class="text-dark">{{ $archivedInvoice->amount }}</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Description'):</h5>
                        <p class="text-dark">{{ $archivedInvoice->description ?? __('translation.not_found') }}</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.ParentCategory'):</h5>
                        <p class="text-dark">
                            {{ $archivedInvoice->category ? $archivedInvoice->category->name : '-' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.OriginalInvoice'):</h5>
                        <p class="text-dark">
                            @if($archivedInvoice->originalInvoice)
                                <a href="{{ route('invoices.show', $archivedInvoice->originalInvoice->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> @lang('translation.ViewOriginalInvoice')
                                </a>
                            @else
                                @lang('translation.not_found')
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.ArchivedAt'):</h5>
                        <p class="text-dark">{{ $archivedInvoice->archived_at }}</p>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ route('archived-invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> @lang('translation.BackToArchivedInvoices')
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
