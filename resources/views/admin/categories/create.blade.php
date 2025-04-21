@extends('layouts.master')

@section('title') @lang('translation.AddCategory') @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') @lang('translation.Dashboard') @endslot
    @slot('title') @lang('translation.AddCategory') @endslot
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


            <!-- Create Category Form -->
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h4>@lang('translation.CategoryDetails')</h4>

                <div class="row">


                    <!-- Category Name -->
                    <div class="col-md-6 mb-3">
                        <label for="name">@lang('translation.Name')</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                    </div>
                </div>



                <h4>@lang('translation.SubCategories')</h4>
                <div id="subcategory-container">
                    <div class="row subcategory-row">
                        <!-- Subcategory Name -->
                        <div class="col-md-5 mb-3">
                            <label for="subcategories[0][name]">@lang('translation.SubCategoryName')</label>
                            <input type="text" name="subcategories[0][name]" class="form-control" value="{{ old('subcategories.0.name') }}">
                        </div>



                        <!-- Remove Subcategory Button -->
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-remove-subcategory">
                                <i class="mdi mdi-delete"></i> @lang('translation.Remove')
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Button to Add More Subcategories -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <button type="button" id="add-subcategory" class="btn btn-info">@lang('translation.AddMoreSubCategories')</button>
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
   document.getElementById('add-subcategory').addEventListener('click', function() {
    var container = document.getElementById('subcategory-container');
    var subcategoryIndex = container.querySelectorAll('.subcategory-row').length;
    var newRow = document.createElement('div');
    newRow.classList.add('row', 'subcategory-row');
    newRow.innerHTML = `
        <div class="col-md-5 mb-3">
            <label for="subcategories[${subcategoryIndex}][name]">@lang('translation.SubCategoryName')</label>
            <input type="text" name="subcategories[${subcategoryIndex}][name]" class="form-control">
        </div>
       
        <div class="col-md-2 mb-3 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-remove-subcategory">
                <i class="mdi mdi-delete"></i> @lang('translation.Remove')
            </button>
        </div>
    `;
    container.appendChild(newRow);
    attachRemoveHandlers();
});

// Function to attach click handlers for removing subcategories
function attachRemoveHandlers() {
    document.querySelectorAll('.btn-remove-subcategory').forEach(function(button) {
        button.addEventListener('click', function() {
            var row = this.closest('.subcategory-row');
            row.remove();
        });
    });
}

// Initialize remove handlers on page load for existing rows
attachRemoveHandlers();

</script>
@endsection
