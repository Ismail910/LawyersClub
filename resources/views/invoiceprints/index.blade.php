<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الفواتير</title>
    <style>
        body {
            font-family: 'Arial', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            padding: 20px;
            line-height: 1.6;
            margin: 0;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .header h2 {
            color: #2c3e50;
            margin: 0;
            font-size: 28px;
        }

        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
            align-items: flex-end;
        }

        .form-group {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            padding-right: 15px;
            padding-left: 15px;
            margin-bottom: 1rem;
        }

        label {
            display: inline-block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            display: block;
            width: 100%;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
                        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            cursor: pointer;
        }

        .btn-primary {
            color: #fff;
            background-color: #3498db;
            border-color: #3498db;
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-print {
            background-color: #27ae60;
            color: #fff;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            border: none;
            display: block;
            margin: 30px auto;
            width: 200px;
            text-align: center;
        }

        .btn-print:hover {
            background-color: #219653;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            text-align: right;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
        }

        .table tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .total-display {
            background-color: #3498db;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .total-amount {
            font-size: 24px;
            margin-right: 10px;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .print-section, .print-section * {
                visibility: visible;
            }
            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
            .table {
                font-size: 12px;
            }
            .total-display {
                background-color: #3498db !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container no-print">
        <div class="header">
            <h2>طباعة الفواتير</h2>
        </div>

        <!-- Filter Form -->
        <div class="filter-section">
            <form id="filterForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="from">من تاريخ</label>
                        <input type="date" id="from" name="from" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="to">إلى تاريخ</label>
                        <input type="date" id="to" name="to" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Total Display -->
        <div id="totalDisplay" class="total-display" style="display: none;">
            إجمالي المبالغ: <span id="totalAmount" class="total-amount">0.00</span> جنية
        </div>

        <!-- Invoice Table -->
        <div class="table-responsive">
            <table class="table" id="invoicePrintTable">
                <thead>
                    <tr>
                        <th>الرقم التسلسلي</th>
                        <th>الفئة</th>
                        <th>رقم الفاتورة</th>
                        <th>المبلغ</th>
                        <th>الوصف</th>
                        <th>تاريخ الطباعة</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Print Button -->
        <button id="printButton" class="btn-print">طباعة الفواتير</button>
    </div>

    <!-- Print Section (hidden until printing) -->
    <div id="printSection" style="display: none;"></div>

    <!-- Include local assets -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

    <script>
        let dataTable;
        let totalAmount = 0;

        $(document).ready(function() {
            // Initialize DataTable
            dataTable = $('#invoicePrintTable').DataTable({
        processing: true,
        serverSide: false, // Change to false to disable server-side processing
        ajax: {
            url: '/invoiceprints',
            type: 'GET',
            data: function(d) {
                d.from = $('#from').val();
                d.to = $('#to').val();
            },
            dataSrc: function(json) {
                // Calculate total amount from the data
                totalAmount = json.data.reduce((sum, row) => sum + parseFloat(row.amount || 0), 0);
                $('#totalDisplay').show();
                $('#totalAmount').text(totalAmount.toFixed(2));
                return json.data;
            }
        },
        columns: [
            { data: 'serial_number', name: 'serial_number' },
            { data: 'category_name', name: 'category_name' },
            { data: 'invoice_number', name: 'invoice_number' },
            {
                data: 'amount',
                name: 'amount',
                render: function(data, type, row) {
                    return parseFloat(data || 0).toFixed(2);
                }
            },
            { data: 'description', name: 'description' },
            {
                data: 'printed_at',
                name: 'printed_at',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('ar-EG') : '';
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json'
        },
        dom: '<"top"fl>rt<"bottom"ip>',
        paging: false, // Disable pagination
        scrollY: "600px", // Add vertical scrolling
        scrollCollapse: true
    });

            // Set default dates
            setDefaultDates();

            // Filter form submission
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                dataTable.ajax.reload();
            });

            // Print button click
            $('#printButton').on('click', function() {
                printTable();
            });
        });

        function setDefaultDates() {
            const currentYear = new Date().getFullYear();
            const startOfYear = `${currentYear}-01-01`;
            const endOfYear = `${currentYear}-12-31`;

            $('#from').val(startOfYear);
            $('#to').val(endOfYear);
        }

        function printTable() {
            // Get the HTML of the table and total display
            const totalDisplayHtml = $('#totalDisplay').clone().wrap('<div></div>').parent().html();
            const tableHtml = $('#invoicePrintTable').clone().wrap('<div></div>').parent().html();

            // Get all rows
            const rows = $('#invoicePrintTable tbody tr').get();
            const rowsPerPage = 15;
            const pageCount = Math.ceil(rows.length / rowsPerPage);

            // Create a print section
            const printSection = $('#printSection');
            printSection.empty();

            // Add company header
            printSection.append(`
                <div style="text-align: center; margin-bottom: 20px;">
                    <h2 style="margin: 0; color: #2c3e50;">كشف الفواتير المطبوعة</h2>
                    <p style="margin: 5px 0; color: #7f8c8d;">الفترة من ${$('#from').val()} إلى ${$('#to').val()}</p>
                </div>
            `);

            // Add total display
            printSection.append(totalDisplayHtml);

            // Process pages
            for (let i = 0; i < pageCount; i++) {
                // Create a table for each page
                const pageTable = $('<table class="table" style="width: 100%; border-collapse: collapse; margin-top: 10px;"></table>');

                // Add header
                pageTable.append($('#invoicePrintTable thead').clone());

                // Add body with current page rows
                const tbody = $('<tbody></tbody>');
                const start = i * rowsPerPage;
                const end = start + rowsPerPage;

                for (let j = start; j < end && j < rows.length; j++) {
                    tbody.append($(rows[j]).clone());
                }

                pageTable.append(tbody);

                // Add footer with total for the page (if needed)
                const pageTotal = rows.slice(start, end).reduce((sum, row) => {
                    return sum + parseFloat($(row).find('td:eq(3)').text() || 0);
                }, 0);

                pageTable.append(`
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: left; font-weight: bold;">إجمالي الصفحة</td>
                            <td style="font-weight: bold;">${pageTotal.toFixed(2)}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                `);

                // Add to print section
                printSection.append(pageTable);

                // Add page break except for last page
                if (i < pageCount - 1) {
                    printSection.append('<div class="page-break"></div>');
                }
            }

            // Add final total
            printSection.append(`
                <div style="margin-top: 20px; text-align: left; font-weight: bold; font-size: 16px;">
                    الإجمالي العام: ${totalAmount.toFixed(2)} جنية
                </div>
            `);

            // Trigger print
            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>طباعة الفواتير</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                direction: rtl;
                                padding: 20px;
                                color: #333;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 10px;
                                font-size: 14px;
                            }
                            th, td {
                                border: 1px solid #ddd;
                                padding: 8px;
                                text-align: right;
                            }
                            th {
                                background-color: #f2f2f2;
                                font-weight: bold;
                            }
                            .total-display {
                                background-color: #3498db;
                                color: white;
                                padding: 10px;
                                border-radius: 5px;
                                margin-bottom: 15px;
                                font-size: 16px;
                                font-weight: bold;
                                text-align: center;
                            }
                            .page-break {
                                page-break-after: always;
                            }
                            @page {
                                size: A4;
                                margin: 10mm;
                            }
                        </style>
                    </head>
                    <body>
                        ${printSection.html()}
                        <script>
                            window.onload = function() {
                                setTimeout(function() {
                                    window.print();
                                    window.close();
                                }, 200);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>
</body>
</html>
