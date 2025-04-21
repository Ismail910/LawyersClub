@extends('layouts.master')

@section('title') @lang('translation.CategoryDetails') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Categories') @endslot
    @slot('title') @lang('translation.CategoryDetails') @endslot
@endcomponent

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">@lang('translation.CategoryDetails')</h4>
                </div>
                <div class="card-body">
                    <!-- Category Information -->
                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Name'):</h5>
                        <p class="fw-bold text-dark">{{ $category->name }}</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Description'):</h5>
                        <p class="text-dark">{{ $category->description ?? __('translation.not_found') }}</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.ParentCategory'):</h5>
                        <p class="text-dark">{{ $category->parent->name ?? '-' }}</p>
                    </div>

                    <!-- Subcategories Section -->
                    <div class="mb-3">
                        <h5 class="text-muted">@lang('translation.Subcategories')</h5>
                        @if($category->subcategories->isNotEmpty())
                            <ul class="list-group">
                                @foreach($category->subcategories as $subcategory)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">{{ $subcategory->name }}</span>
                                        <a href="{{ route('categories.show', $subcategory->id) }}" class="btn btn-sm btn-info">@lang('translation.View')</a>
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
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> @lang('translation.BackToCategories')
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
