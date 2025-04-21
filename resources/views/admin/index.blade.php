@extends('layouts.master')

@section('title') @lang('translation.Dashboards') @endsection

@section('content')
    <div class="container mt-5">
        <!-- Logo -->
        <div class="text-center mb-4 d-print-none">
            <img src="{{ URL::asset('assets/images/logo.jpeg') }}" alt="Logo" class="img-fluid" style="max-height: 120px;">
        </div>

        <!-- Date Filter Form -->
        <form method="GET" class="row g-3 justify-content-center d-print-none">
            <div class="col-md-4">
                <label for="start_date" class="form-label">من تاريخ</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label">إلى تاريخ</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">تصفية</button>
            </div>
        </form>

        <!-- Print Button -->
        <div class="text-center my-4 d-print-none">
            <button onclick="printReport()" class="btn btn-success">
                <i class="fas fa-print me-2"></i>طباعة التقرير
            </button>
        </div>

        <!-- Report Header (Visible only when printing) -->
        <div class="d-none d-print-block text-center mb-4">
            <img src="{{ URL::asset('assets/images/logo.jpeg') }}" alt="Logo" style="height: 100px;">
            <h2 class="mt-2">تقرير المالية</h2>
            <p class="text-muted">
                الفترة من {{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}
                إلى {{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}
            </p>
        </div>

        <!-- Totals Display -->
        <div class="row mt-3 text-center">
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">الإيرادات</h5>
                        <p class="card-text fs-4">{{ number_format($totalRevenues, 2) }} ج.م</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-danger">
                    <div class="card-body">
                        <h5 class="card-title text-danger">المصروفات</h5>
                        <p class="card-text fs-4">{{ number_format($totalExpenses, 2) }} ج.م</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-body">
                        <h5 class="card-title text-info">الرصيد</h5>
                        <p class="card-text fs-4">{{ number_format($difference, 2) }} ج.م</p>
                    </div>
                </div>
            </div>
        </div>

   <!-- Print-Only Section -->
<div id="print-section" class="d-none d-print-block">
    <div class="text-center mb-4">
        <img src="{{ URL::asset('assets/images/logo.jpeg') }}" alt="Logo" style="height: 100px;">
        <h2 class="mt-2">تقرير المالية</h2>
        <p class="text-muted">
            الفترة من {{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}
            إلى {{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}
        </p>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th style="width: 33%">الإيرادات</th>
                    <th style="width: 33%">المصروفات</th>
                    <th style="width: 33%">الرصيد</th>
                </tr>
            </thead>
            <tbody>
                <tr style="height: 100px;">
                    <td class="align-middle text-success fs-3 fw-bold">
                        {{ number_format($totalRevenues, 2) }} ج.م
                    </td>
                    <td class="align-middle text-danger fs-3 fw-bold">
                        {{ number_format($totalExpenses, 2) }} ج.م
                    </td>
                    <td class="align-middle text-primary fs-3 fw-bold">
                        {{ number_format($difference, 2) }} ج.م
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


        <!-- Detailed Tables -->
        <div class="row mt-4">
            <!-- Revenues Table -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">تفاصيل الإيرادات</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>المبلغ</th>
                                        <th>الوصف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(APP\Models\Budget::whereBetween('created_at', [$startDate, $endDate])->get() as $revenue)
                                    <tr>
                                        <td>{{ $revenue->created_at->format('Y-m-d') }}</td>
                                        <td>{{ number_format($revenue->amount, 2) }} ج.م</td>
                                        <td>{{ $revenue->notes ?? '--' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th colspan="2">الإجمالي</th>
                                        <th>{{ number_format($totalRevenues, 2) }} ج.م</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses Table -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">تفاصيل المصروفات</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>المبلغ</th>
                                        <th>الوصف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(APP\Models\Invoice::whereBetween('created_at', [$startDate, $endDate])->get() as $expense)
                                    <tr>
                                        <td>{{ $expense->created_at->format('Y-m-d') }}</td>
                                        <td>{{ number_format($expense->amount, 2) }} ج.م</td>
                                        <td>{{ $expense->notes ?? '--' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th colspan="2">الإجمالي</th>
                                        <th>{{ number_format($totalExpenses, 2) }} ج.م</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signature Section (Visible only when printing) -->
        <div class="d-none d-print-block mt-5">
            <div class="row">
                <div class="col-6 text-center">
                    <div class="signature-line"></div>
                    <p>المسئول المالي</p>
                </div>
                <div class="col-6 text-center">
                    <div class="signature-line"></div>
                    <p>مدير المؤسسة</p>
                </div>
            </div>
            <div class="text-center mt-3 text-muted">
                تم الطباعة في {{ \Carbon\Carbon::now()->format('Y-m-d H:i') }}
            </div>
        </div>
    </div>

    <style>
        @media print {
            body {
                background-color: white;
                font-size: 12pt;
                padding: 0;
                margin: 0;
            }
            .container {
                width: 100%;
                max-width: 100%;
                padding: 10mm;
            }
            .table {
                page-break-inside: avoid;
            }
            .card {
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
            .signature-line {
                border-top: 1px dashed #000;
                width: 200px;
                margin: 20px auto;
                padding-top: 10px;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            .d-print-none {
    display: none !important;
}
.d-print-block {
    display: block !important;
}

        }
    </style>
<script>
    function printReport() {
        const printContents = document.getElementById('print-section').innerHTML;

        const printWindow = window.open('', '', 'width=900,height=650');
        printWindow.document.write(`
            <html dir="rtl" lang="ar">
                <head>
                    <title>طباعة التقرير</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            padding: 20px;
                            direction: rtl;
                        }
                        .text-center { text-align: center; }
                        .mb-4 { margin-bottom: 1.5rem; }
                        h2 { font-size: 24px; margin-top: 10px; }
                        p { font-size: 16px; margin: 0; }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                            font-size: 18px;
                        }
                        th, td {
                            border: 1px solid #000;
                            padding: 10px;
                            text-align: center;
                        }
                        th {
                            background-color: #343a40;
                            color: #fff;
                        }
                        .text-success { color: green; }
                        .text-danger { color: red; }
                        .text-primary { color: blue; }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script>


@endsection
