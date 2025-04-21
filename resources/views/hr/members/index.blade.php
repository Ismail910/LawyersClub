@extends('layouts.master')

@section('title') @lang('translation.Members') @endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') @lang('translation.Dashboard') @endslot
        @slot('title') @lang('translation.Members') @endslot
    @endcomponent

    @component('components.add-button', [
        'route' => route('hr.members.create'),
        'label' => __('translation.add_member')
    ])
    @endcomponent

    <div class="container-full">
        <form action="{{ route('members.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="file">تحميل ملف الأعضاء (Excel)</label>
            <input type="file" name="file" required accept=".xlsx, .xls, .csv">

            <button type="submit">رفع الملف</button>
        </form>

        <!-- Filter Section -->
        <div class="row mt-4">
            <div class="col-md-3">
                <label>@lang('translation.name')</label>
                <input type="text" id="name" class="form-control" placeholder="@lang('translation.name')">
            </div>

            <div class="col-md-3">
                <label>@lang('translation.membership_date')</label>
                <input type="text" id="membership_date_start" class="form-control" placeholder="@lang('translation.start_date')">
                <input type="text" id="membership_date_end" class="form-control" placeholder="@lang('translation.end_date')">
            </div>

            <div class="col-md-3">
                <label>@lang('translation.last_payment_year')</label>
                <input type="text" id="last_payment_year" class="form-control" placeholder="@lang('translation.last_payment_year')">
            </div>

            <div class="col-md-3">
                <label>@lang('translation.gender')</label>
                <select id="gender" class="form-control">
                    <option value="">@lang('translation.select_gender')</option>
                    <option value="ذكر">@lang('translation.male')</option>
                    <option value="أنثى">@lang('translation.female')</option>
                </select>
            </div>
        </div>

        <!-- DataTable -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered yajra-datatable w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('translation.name')</th>
                        <th>@lang('translation.phone')</th>
                        <th>@lang('translation.job_title')</th>
                        <th>@lang('translation.membership_section')</th>
                        <th>@lang('translation.membership_number')</th>
                        <th>@lang('translation.membership_date')</th>
                        <th>@lang('translation.address')</th>
                        <th>@lang('translation.payment_voucher_number')</th>
                        <th>@lang('translation.last_payment_year')</th>
                        <th>@lang('translation.printing_status')</th>
                        <th>@lang('translation.amount')</th>
                        <th>@lang('translation.notes')</th>
                        <th>@lang('translation.printing_and_payment_date')</th>
                        <th>@lang('translation.payment_date')</th>
                        <th>@lang('translation.current_year_paid')</th>
                        <th>@lang('translation.voting_right')</th>
                        <th>@lang('translation.gender')</th>
                        <th>@lang('translation.actions')</th>
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
                url: "{{ route('hr.members.index') }}",
                data: function(d) {
                    // Send the filter data to the backend
                    d.name = $('#name').val();
                    d.membership_date_start = $('#membership_date_start').val();
                    d.membership_date_end = $('#membership_date_end').val();
                    d.last_payment_year = $('#last_payment_year').val();
                    d.gender = $('#gender').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'phone', name: 'phone'},
                {data: 'job_title', name: 'job_title'},
                {data: 'membership_section', name: 'membership_section', orderable: false, searchable: false},
                {data: 'membership_number', name: 'membership_number'},
                {data: 'membership_date', name: 'membership_date'},
                {data: 'address', name: 'address'},
                {data: 'payment_voucher_number', name: 'payment_voucher_number'},
                {data: 'last_payment_year', name: 'last_payment_year'},
                {data: 'printing_status', name: 'printing_status'},
                {data: 'amount', name: 'amount'},
                {data: 'notes', name: 'notes'},
                {data: 'printing_and_payment_date', name: 'printing_and_payment_date'},
                {data: 'payment_date', name: 'payment_date'},
                {data: 'current_year_paid', name: 'current_year_paid'},
                {data: 'voting_right', name: 'voting_right'},
                {data: 'gender', name: 'gender'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        // Delete confirmation
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

        // Filter DataTable on change
        $('#name, #membership_date_start, #membership_date_end, #last_payment_year, #gender').on('change', function () {
            table.draw();
        });
    });
</script>
@endsection
