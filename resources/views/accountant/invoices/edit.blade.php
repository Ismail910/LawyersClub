@extends('layouts.master')

@section('title') @lang('translation.EditInvoice') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.EditInvoice') @endslot
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

            <!-- Edit Invoice Form -->
            <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h4>@lang('translation.InvoiceDetails')</h4>

                <div class="row">
                    <!-- Parent Category -->
                    <div class="col-md-6 mb-3">
                        <label for="parent_category">@lang('translation.ParentCategory')</label>
                        <select name="parent_category" id="parent_category" class="form-control" required>
                            <option value="">@lang('translation.SelectParentCategory')</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_category', $invoice->category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category (Child Category) -->
                    <div class="col-md-6 mb-3">
                        <label for="category_id">@lang('translation.Category')</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">@lang('translation.SelectCategory')</option>
                            <!-- This will be populated dynamically using JavaScript -->
                        </select>
                    </div>

                    <!-- Invoice Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name">@lang('translation.Name')</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $invoice->name) }}" required>
                    </div>

                    <!-- Invoice Number -->
                    <div class="col-md-6 mb-3">
                        <label for="invoice_number">@lang('translation.InvoiceNumber')</label>
                        <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Invoice Amount -->
                    <div class="col-md-6 mb-3">
                        <label for="amount">@lang('translation.Amount')</label>
                        <input type="string" name="amount" id="amount" class="form-control" value="{{ old('amount', $invoice->amount) }}" step="0.01" min="0" required>
                    </div>
                </div>



                <div class="row">
                    <!-- Invoice Description -->
                    <div class="col-md-12 mb-3">
                        <label for="description">@lang('translation.Description')</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $invoice->description) }}</textarea>
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

@section('script')
<script>
    // This script will update the child categories dynamically when a parent category is selected.
    document.getElementById('parent_category').addEventListener('change', function() {
        let parentId = this.value;
        let categorySelect = document.getElementById('category_id');

        // Clear existing options
        categorySelect.innerHTML = '<option value="">@lang('translation.SelectCategory')</option>';

        if (parentId) {
            // Fetch subcategories for the selected parent category
            fetch(`/categories/${parentId}/children`)
                .then(response => response.json())
                .then(data => {
                    // Populate the categories dropdown
                    data.forEach(category => {
                        let option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;

                        // Preselect the category based on the old input or the invoice's category
                        if ('{{ old('category_id', $invoice->category_id) }}' == category.id) {
                            option.selected = true;
                        }

                        categorySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching categories:', error));
        }
    });

    // Trigger a change event on page load to populate the child categories (if the parent category is already selected)
    document.addEventListener('DOMContentLoaded', function() {
        let parentId = document.getElementById('parent_category').value;
        if (parentId) {
            document.getElementById('parent_category').dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
