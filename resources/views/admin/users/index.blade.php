@extends('layouts.master')

@section('title') @lang('translation.Users') @endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') @lang('translation.Dashboard') @endslot
        @slot('title') @lang('translation.Users') @endslot
    @endcomponent

    @component('components.add-button', [
        'route' => route('users.create'),
        'label' => __('translation.add_user')
    ])
    @endcomponent

    <div class="container-full">
        <!-- Status Filter Dropdown -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select name="users_status" class="form-control custom-select" id="users_status">
                    <option value="">@lang('translation.select_status')</option>
                    <option value="0">@lang('translation.active')</option>
                    <option value="1">@lang('translation.inactive')</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered yajra-datatable w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('translation.image')</th>
                        <th>@lang('translation.username')</th>
                        <th>@lang('translation.phone')</th>
                        <th>@lang('translation.email')</th>
                        <th>@lang('translation.user_type')</th>
                        <th>@lang('translation.points_balance')</th>
                        <th>@lang('translation.location')</th>
                        <th>@lang('translation.city')</th>
                        <th>@lang('translation.region')</th>
                        <th>@lang('translation.invite_code')</th>
                        <th>@lang('translation.verification_status')</th>
                        <th>@lang('translation.invited_count')</th>
                        <th>@lang('translation.registration_date')</th>
                        <th>@lang('translation.details')</th>
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
    var deleteConfirmMsg = "{{ __('messages.delete_confirm') }}";
    var cancelMsg = "{{ __('messages.cancel') }}";
    var yesMsg = "{{ __('messages.yes_delete') }}";

    $(document).ready(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true, // Enable horizontal scrolling
            scrollY: '400px', // Set vertical scrolling height
            scrollCollapse: true, // Collapse the table if the content is less than the set height
            ajax: {
                url: "{{ route('users.show') }}",
                data: function (d) {
                    d.status = $('#users_status').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'img', name: 'img', orderable: false, searchable: false},
                {data: 'username', name: 'username'},
                {data: 'phone', name: 'phone'},
                {data: 'email', name: 'email'},
                {data: 'user_type', name: 'user_type'},
                {data: 'points_balance', name: 'points_balance'},
                {data: 'location', name: 'location'},
                {data: 'city', name: 'city'},
                {data: 'region', name: 'region'},
                {data: 'invite_code', name: 'invite_code'},
                {data: 'verification_status', name: 'verification_status'},
                {data: 'invited_count', name: 'invited_count'},
                {data: 'registration_date', name: 'registration_date'},

                {data: 'details', name: 'details', orderable: false, searchable: false},
            ],


            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/{{ app()->getLocale() }}.json"
            }
        });

        // Reload table data based on the selected status
        $('#users_status').change(function() {
            table.ajax.reload();
        });

        // Confirmation and deletion handler for user deletion
        $(document).on('click', '.delete-object', function(e) {
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

        // Toggle user status
        $(document).on('change', '.toggle-switch', function() {
            var checkbox = $(this);
            var userId = checkbox.data('id');
            var isChecked = checkbox.is(':checked');

            $.ajax({
                url: `{{ route('users.toggleStatus', ':id') }}`.replace(':id', userId),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: isChecked ? 1 : 0
                },
                success: function(response) {
                    if (response.success) {
                        checkbox.next('label').text(isChecked ? '@lang("translation.inactive")' : '@lang("translation.active")');
                        table.ajax.reload(null, false); // Refresh the table without a full reload
                    }
                },
                error: function() {
                    Swal.fire("Error", "Unable to update activation status.", "error");
                }
            });
        });
    });
</script>
@endsection
