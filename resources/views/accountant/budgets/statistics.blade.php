@extends('layouts.master')

@section('title') @lang('translation.Budget Statistics') @endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') @lang('translation.Dashboard') @endslot
        @slot('title') @lang('translation.Budget Statistics') @endslot
    @endcomponent

    <!-- 🔹 Filter Form -->
    <form id="filter-form" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="parent_category">@lang('translation.ParentCategory')</label>
                <select name="parent_category" id="parent_category" class="form-control" required>
                    <option value="">@lang('translation.SelectParentCategory')</option>
                    <option value="all">@lang('translation.SelectAll')</option> <!-- Select All option -->
                    @foreach($categories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="category_id">@lang('translation.Category')</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">@lang('translation.SelectCategory')</option>
                    <option value="all">@lang('translation.SelectAll')</option> <!-- Select All option -->
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>@lang('translation.From Date')</label>
                <input type="date" name="from_date" id="from-date" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>@lang('translation.To Date')</label>
                <input type="date" name="to_date" id="to-date" class="form-control">
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary w-100">@lang('translation.Filter')</button>
            </div>
        </div>
    </form>

    <!-- 🔹 Statistics Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-calendar-alt fa-2x me-3"></i>
                    <div>
                        <h6 class="text-uppercase">@lang('translation.Monthly Statistics')</h6>
                        <h3 id="monthly-stats" class="fw-bold mb-0">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-chart-line fa-2x me-3"></i>
                    <div>
                        <h6 class="text-uppercase">@lang('translation.Yearly Statistics')</h6>
                        <h3 id="yearly-stats" class="fw-bold mb-0">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-dollar-sign fa-2x me-3"></i>
                    <div>
                        <h6 class="text-uppercase">@lang('translation.Total Amount')</h6>
                        <h3 id="total-amount" class="fw-bold mb-0">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="text-uppercase"><i class="fas fa-list-ul me-2"></i>@lang('translation.Category-wise Statistics')</h5>
            <div id="category-stats" class="mt-3"></div>
        </div>
    </div>

    <!-- 🔹 Budget Records Table -->
    <div class="table-responsive mt-4">
        <h4>@lang('translation.Budget Records')</h4>
        <table class="table table-bordered yajra-datatable w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('translation.tenant_name')</th>
                    <th>@lang('translation.Category')</th>
                    <th>@lang('translation.Amount')</th>
                    <th>@lang('translation.CreatedAt')</th>
                    <th>@lang('translation.Notes')</th>
                    <th>@lang('translation.Action')</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        let today = new Date();
        let currentYear = today.getFullYear();
        let currentMonth = today.getMonth(); // 0-based (0 = January, 6 = July)

        // Determine fiscal year start and end dates
        let fiscalStartYear, fiscalEndYear;

        if (currentMonth >= 6) { // July (6) or later - current fiscal year
            fiscalStartYear = currentYear;
            fiscalEndYear = currentYear + 1;
        } else { // Before July - previous fiscal year
            fiscalStartYear = currentYear - 1;
            fiscalEndYear = currentYear;
        }

        // Set fiscal year dates: July 1st to June 30th
        let firstDay = new Date(fiscalStartYear, 6, 1).toISOString().split('T')[0]; // July 1st
        let lastDay = new Date(fiscalEndYear, 5, 30).toISOString().split('T')[0]; // June 30th

        $('#from-date').val(firstDay);
        $('#to-date').val(lastDay);

        // Fetch subcategories when a parent category is selected
        $('#parent_category').change(function() {
            var parentId = $(this).val();
            if (parentId === "all") {
                $('#category_id').html('<option value="all">@lang('translation.SelectAll')</option>');
            } else if (parentId) {
                $.ajax({
                    url: "/categories/" + parentId + "/children",
                    method: 'GET',
                    success: function(response) {
                        var options = '<option value="all">@lang('translation.SelectAll')</option>';
                        response.forEach(function(category) {
                            options += `<option value="${category.id}">${category.name}</option>`;
                        });
                        $('#category_id').html(options);
                    }
                });
            } else {
                $('#category_id').html('<option value="all">@lang('translation.SelectAll')</option>');
            }
        });

        // Function to fetch statistics based on filters
        function fetchStatistics(formData = '') {
            $.ajax({
                url: "{{ route('budget.statistics') }}",
                method: 'GET',
                data: formData,
                success: function (response) {
                    $('#monthly-stats').html(response.monthly.reduce((acc, stat) => acc + stat.total_amount, 0).toLocaleString());
                    $('#yearly-stats').html(response.yearly.reduce((acc, stat) => acc + stat.total_amount, 0).toLocaleString());
                    $('#total-amount').html(response.total.toLocaleString());

                    // Update category-wise statistics
                    $('#category-stats').html(response.categories.map(stat => `
                        <div class="d-flex justify-content-between p-2 border-bottom">
                            <span>${stat.category_name}</span>
                            <strong>${stat.total_amount.toLocaleString()}</strong>
                        </div>
                    `).join(''));

                    loadDataTable(response.budgetData); // Populate DataTable with the budget data
                }
            });
        }

        // Function to load DataTable with formatted data
        function loadDataTable(data) {
            data.forEach((item, index) => {
                item.DT_RowIndex = index + 1; // Row index starts at 1

                // Format created_at to y-m-d (e.g., 25-03-2025)
                if (item.created_at) {
                    let createdAt = new Date(item.created_at);
                    item.created_at = createdAt.getFullYear().toString().slice(-2) + '-' +
                                      ('0' + (createdAt.getMonth() + 1)).slice(-2) + '-' +
                                      ('0' + createdAt.getDate()).slice(-2); // Format as yy-mm-dd
                }
            });

            // Initialize DataTable
            $('.yajra-datatable').DataTable({
                data: data,
                processing: true,
                serverSide: false,
                scrollX: true,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'tenant_name', name: 'tenant_name'},
                    {data: 'category_name', name: 'category_name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'created_at', name: 'created_at'}, // Display the formatted created_at
                    {data: 'notes', name: 'notes'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/{{ app()->getLocale() }}.json"
                },
                destroy: true,
                pageLength: 5,
            });
        }

        // Fetch initial statistics and populate DataTable
        fetchStatistics($('#filter-form').serialize());

        // Event listener for filter form submission
        $('#filter-form').on('submit', function (e) {
            e.preventDefault();
            fetchStatistics($(this).serialize());
        });
    });
</script>

@endsection

@endsection
