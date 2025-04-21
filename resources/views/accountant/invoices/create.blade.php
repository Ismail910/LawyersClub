@extends('layouts.master')

@section('title') @lang('translation.AddInvoice') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.AddInvoice') @endslot
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

            <!-- Create Invoice Form -->
            <form action="{{ route('invoices.store') }}" method="POST">
                @csrf

                <h4>@lang('translation.InvoiceDetails')</h4>

                <div class="row">
                    <!-- Parent Category -->
                    <div class="col-md-6 mb-3">
                        <label for="parent_category">@lang('translation.ParentCategory')</label>
                        <select name="parent_category" id="parent_category" class="form-control" required>
                            <option value="">@lang('translation.SelectParentCategory')</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_category') == $parent->id ? 'selected' : '' }}>
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
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <!-- Invoice Number -->
                    <div class="col-md-6 mb-3">
                        <label for="invoice_number">@lang('translation.InvoiceNumber')</label>
                        <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ old('invoice_number') }}" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Invoice Amount -->
                    <div class="col-md-6 mb-3">
                        <label for="amount">@lang('translation.Amount')</label>
                        <input type="string" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" step="0.01" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Invoice Description -->
                    <div class="col-md-12 mb-3">
                        <label for="description">@lang('translation.Description')</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
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

@section('script')
<script>
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
                console.log('Fetched categories:', data); // Debugging the fetched data

                // If data is empty, show an alert
                if (data.length === 0) {
                    alert('No categories found for the selected parent category.');
                }

                // Populate the categories dropdown
                data.forEach(category => {
                    let option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;

                    // Check if the old value matches this category (for form re-population)
                    if ('{{ old('category_id') }}' == category.id) {
                        option.selected = true;
                    }

                    categorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
                alert('Error fetching categories.');
            });
    }
});

</script>
@endsection
