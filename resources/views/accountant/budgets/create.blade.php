@extends('layouts.master')

@section('title') @lang('translation.AddBudget') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.AddBudget') @endslot
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

            <!-- Create Budget Form -->
            <form action="{{ route('budgets.store') }}" method="POST">
                @csrf

                <h4>@lang('translation.BudgetDetails')</h4>

                <div class="row">
                    <!-- Budget Category -->
                    <div class="col-md-6 mb-3">
                        <label for="parent_category">@lang('translation.ParentCategory')</label>
                        <select name="parent_category" id="parent_category" class="form-control" required>
                            <option value="">@lang('translation.SelectParentCategory')</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="category_id">@lang('translation.Category')</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">@lang('translation.SelectCategory')</option>
                            <!-- سيتم تعبئة هذا الخيار باستخدام JavaScript -->
                        </select>
                    </div>


                    <!-- Budget Amount -->
                    <div class="col-md-6 mb-3">
                        <label for="tenant_name">@lang('translation.tenant_name')</label>
                        <input type="string" name="tenant_name" id="tenant_name" class="form-control" value="{{ old('tenant_name') }}" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="amount">@lang('translation.Amount')</label>
                        <input type="string" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" step="0.01" min="0" required>
                    </div>

                </div>



                <div class="row">
                    <!-- Budget Notes -->
                    <div class="col-md-12 mb-3">
                        <label for="notes">@lang('translation.Notes')</label>
                        <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
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
<script>
    document.getElementById('parent_category').addEventListener('change', function() {
        let parentId = this.value;
        let categorySelect = document.getElementById('category_id');

        // مسح الخيارات القديمة
        categorySelect.innerHTML = '<option value="">@lang('translation.SelectCategory')</option>';

        if (parentId) {
            fetch(`/categories/${parentId}/children`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(category => {
                        let option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        categorySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching categories:', error));
        }
    });
    </script>

@endsection
