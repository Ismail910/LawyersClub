@extends('layouts.master')

@section('title') @lang('translation.MembershipSections') @endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') @lang('translation.Dashboard') @endslot
        @slot('title') @lang('translation.MembershipSections') @endslot
    @endcomponent

    @component('components.add-button', [
        'route' => route('hr.membership-sections.create'),
        'label' => __('translation.add_membership_section')
    ])
    @endcomponent

    <div class="container-full">
        <div class="table-responsive">
            <table class="table table-bordered yajra-datatable w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('translation.name')</th>
                        <th>@lang('translation.description')</th>
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

        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: "{{ route('hr.membership-sections.index') }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/{{ app()->getLocale() }}.json"
            }
        });

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
