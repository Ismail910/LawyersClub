@extends('layouts.master')

@section('title') @lang('translation.Invoices') @endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') @lang('translation.Dashboard') @endslot
        @slot('title') @lang('translation.Invoices') @endslot
    @endcomponent

    @component('components.add-button', [
        'route' => route('invoices.create'),
        'label' => __('translation.add_invoice')
    ])
    @endcomponent

    <div class="container-full">
        <!-- Category Filter -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label>@lang('translation.Filter by Category')</label>
                <select id="categoryFilter" class="form-control">
                    <option value="">@lang('translation.Select Category')</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered yajra-datatable w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('translation.name')</th>
                        <th>@lang('translation.InvoiceNumber')</th>
                        <th>@lang('translation.Category')</th>
                        <th>@lang('translation.Amount')</th>
                        <th>@lang('translation.Actions')</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var deleteConfirmMsg = "{{ __('messages.delete_confirm') }}";
        var cancelMsg = "{{ __('messages.cancel') }}";
        var yesMsg = "{{ __('messages.yes_delete') }}";

        // Initialize DataTable
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: "{{ route('invoices.index') }}",
                data: function(d) {
                    // Add custom category filter to the request data
                    d.category_id = $('#categoryFilter').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'invoice_number', name: 'invoice_number'},
                {data: 'category', name: 'category'},
                {data: 'amount', name: 'amount'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/{{ app()->getLocale() }}.json"
            }
        });

        // Filter by Category
        $('#categoryFilter').on('change', function() {
            table.draw();  // Redraw the table with the updated category filter
        });

        // Delete button confirmation
        $(document).on('click', '.delete-object', function (e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: deleteConfirmMsg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: yesMsg,
                cancelButtonText: cancelMsg,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
