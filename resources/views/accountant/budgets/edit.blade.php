@extends('layouts.master')

@section('title') @lang('translation.EditBudget') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.EditBudget') @endslot
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

            <!-- Edit Budget Form -->
            <form action="{{ route('budgets.update', $budget->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h4>@lang('translation.BudgetDetails')</h4>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="parent_category">@lang('translation.ParentCategory')</label>
                        <select id="parent_category" name="parent_category" class="form-control">
                            <option value="">اختر الفئة الرئيسية</option>
                            @foreach($parentCategories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $budget->category->parent_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="category_id">@lang('translation.Category')</label>
                        <select id="subcategory" name="category_id" class="form-control">
                            <option value="">اختر الفئة الفرعية</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}"
                                    {{ $budget->category_id == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Budget Amount -->
                    <div class="col-md-6 mb-3">
                        <label for="tenant_name">@lang('translation.tenant_name')</label>
                        <input type="string" name="tenant_name" id="tenant_name" class="form-control" value="{{ old('tenant_name', $budget->tenant_name) }}"  step="0.01" min="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="amount">@lang('translation.Amount')</label>
                        <input type="string" name="amount" id="amount" class="form-control" value="{{ old('amount', $budget->amount) }}"  step="0.01" min="0" required>
                    </div>
                </div>


                <div class="row">
                    <!-- Budget Notes -->
                    <div class="col-md-12 mb-3">
                        <label for="notes">@lang('translation.Notes')</label>
                        <textarea name="notes" id="notes" class="form-control">{{ old('notes', $budget->notes) }}</textarea>
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
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#parent_category').on('change', function () {
            let parentId = $(this).val();
            if (parentId) {
                $.ajax({
                    url: '/categories/' + parentId + '/children',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#subcategory').empty().append('<option value="">اختر الفئة الفرعية</option>');
                        $.each(data, function (index, value) {
                            $('#subcategory').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    },
                    error: function () {
                        console.error('Failed to fetch subcategories.');
                    }
                });
            } else {
                $('#subcategory').empty().append('<option value="">اختر الفئة الفرعية</option>');
            }
        });
    });
</script>


@endsection
