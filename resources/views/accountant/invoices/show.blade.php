<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل أمر صرف شيكات</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            line-height: 1.6;
            margin: 0;
        }
        .receipt-container {
            border: 2px solid #ddd;
            padding: 30px;
            background-color: #fff;
            width: 90%;
            margin: 20px auto;
            text-align: center;
            font-size: 18px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .receipt-header {
            font-weight: bold;
            font-size: 24px;
            color: #34495e;
            margin-bottom: 20px;
        }
        .receipt-body {
            text-align: right;
            margin: 20px 0;
        }
        .receipt-line {
            margin-bottom: 15px;
            font-size: 18px;
            color: #2c3e50;
        }
        .amount-table {
            display: inline-table;
            text-align: center;
            margin-top: 0 ;
            border-collapse: separate;
            border-spacing: 10px 5px;
        }
        .amount-table th {
            font-weight: bold;
            padding: 5px;
        }
        .amount-table td {

            font-size: 20px;
            padding: 5px;
        }
        .date-line {
            text-align: right;
            margin: 0;
            font-size: 18px;
        }
        #subcategory-container {
            display: none; /* مخفي افتراضياً */
        }
        .btn-print {
            margin: 20px auto;
            display: block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            border: none;
        }

    .receipt-footer {
        font-size: 16px;
        margin: 50px 0 30px;
        display: flex;
        justify-content: space-between;
        color: #555;
    }
    .signature-line {
        margin-top: 40px; /* المسافة بين الاسم والخط */
        text-align: center;
        width: 150px; /* طول الخط تحت كل توقيع */
    }
    @media print {
        .signature-line {
            margin-top: 30px;
        }
    }

        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .btn-print {
                display: none;
            }
            .receipt-container {
                page-break-inside: avoid;
                margin: 0 auto;
            }
        }
        @page {
            size: A4;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="btn-print" onclick="printReceipt()">طباعة أمر الصرف</button>

        <!-- النسخة الأصلية -->
        <div id="receipt-container" class="receipt-container">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <!-- رابطة الحقوقين باسيوط -->
                <div style="font-size: 36px; font-weight: bold; color: #34495e; flex: 1; text-align: left;">
                    رابطة الحقوقين باسيوط
                </div>

                <!-- أمر صرف شيكات -->
                <div style="flex: 3; text-align: center;">
                    <div style="font-size: 26px; font-weight: bold;">أمر صرف شيكات</div>
                    <div style="font-size: 22px;"> no /  <span id="receipt-id">جاري التحميل...</span></div>
                </div>

                <!-- الشعار -->
                <div style="flex: 1; text-align: right;">
                    <img src="{{ URL::asset('assets/images/logo.jpeg') }}" alt="الشعار" style="height: 130px;">
                </div>
            </div>


            <div class="receipt-body">
                <div class="receipt-line">
                    <strong> بصرف الي السيد / </strong>  <span id="member-name">جاري التحميل...</span>
                </div>

                <div class="receipt-line">
                    <strong>المبلغ  /</strong>
                    <table class="amount-table">
                        <thead>
                            <tr>
                                <th>قرش</th>
                                <th>جنيه</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="piastres">٠٠</td>
                                <td id="pounds">٠٠٠</td>
                            </tr>
                        </tbody>
                    </table>
                    <span id="amount-words">جاري التحميل...</span>
                </div>

                <div class="receipt-line">
                    <strong>رقم الشيك /</strong> <span id="invoice-number">جاري التحميل...</span>
                </div>

                <div class="receipt-line">
                    <strong>وذلك قيمة   /</strong>
                    <span id="category">جاري التحميل...</span>
                    <span id="subcategory-container">
                        <span id="subcategory"></span>
                    </span> /
                    <span id="description">جاري التحميل...</span>
                </div>


            </div>

            <div class="date-line">
                <span id="current-date">جاري التحميل...</span>
            </div>

            <div class="receipt-footer">
                <div>
                    <div>امين خزينه</div>
                    <div class="signature-line">________________</div>
                </div>
                <div>
                    <div>أمين الصندوق</div>
                    <div class="signature-line">________________</div>
                </div>
                <div>
                    <div>سكرتير عام</div>
                    <div class="signature-line">________________</div>
                </div>
            </div>
        </div>

        <div class="receipt-container">
            <div style="text-align: center; font-size: 26px; font-weight: bold; margin-bottom: 20px;">
                إقرار استلام
            </div>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <div class="receipt-line"><strong>الاسم:</strong> ________________</div>
                    <div class="receipt-line"><strong>التوقيع:</strong> ________________</div>
                    <div class="receipt-line"><strong>التاريخ:</strong> ________________</div>
                </div>
                <div style="width: 48%;">
                    <div class="receipt-line"><strong>رقم البطاقة:</strong> ________________</div>
                    <div class="receipt-line"><strong>العنوان:</strong> ________________</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // الدوال المساعدة
        function toArabicDigits(number) {
            const arabicDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            return number.toString().replace(/[0-9]/g, d => arabicDigits[d]);
        }

        function toArabicDigitsIndian(str) {
            const arabicIndianNumerals = {
                '0': '٠', '1': '١', '2': '٢', '3': '٣', '4': '٤',
                '5': '٥', '6': '٦', '7': '٧', '8': '٨', '9': '٩'
            };
            return str.replace(/[0-9]/g, match => arabicIndianNumerals[match] || match);
        }

        function updateCurrentDate() {
            const today = new Date();
            const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
            const formattedDate = today.toLocaleDateString('ar-EG', options);
            const indianDate = toArabicDigitsIndian(formattedDate);

            document.getElementById('current-date').textContent = `تحرير في  /  ${indianDate}`;
        }

        function numberToArabicWords(number) {
            const ones = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة'];
            const teens = ['عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر', 'سبعة عشر', 'ثمانية عشر', 'تسعة عشر'];
            const tens = ['', 'عشرة', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];
            const hundreds = ['', 'مائة', 'مائتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة', 'تسعمائة'];

            function convertBelow1000(n) {
                let word = '';
                if (n >= 100) {
                    word += hundreds[Math.floor(n / 100)];
                    n %= 100;
                    if (n) word += ' و ';
                }
                if (n >= 20) {
                    const t = Math.floor(n / 10), u = n % 10;
                    word += u ? `${ones[u]} و ${tens[t]}` : tens[t];
                } else if (n >= 10) {
                    word += teens[n - 10];
                } else if (n > 0) {
                    word += ones[n];
                }
                return word;
            }

            number = parseFloat(number).toFixed(2);
            const [poundStr, piastreStr] = number.split('.').map(Number);
            let result = '', thousands = Math.floor(poundStr / 1000), rest = poundStr % 1000;

            if (thousands > 0) result += `${convertBelow1000(thousands)} ألف`;
            if (rest > 0) result += (thousands ? ' و ' : '') + `${convertBelow1000(rest)}`;
            if (poundStr > 0) result += ' جنيه';
            if (piastreStr > 0) result += ` و ${convertBelow1000(piastreStr)} قرش`;
            if (!poundStr && !piastreStr) result = 'صفر جنيه';
            return result + ' فقط لا غير';
        }

        // الدوال الرئيسية
        async function fetchInvoiceData() {
            const invoiceId = window.location.pathname.split('/').pop();
            try {
                const response = await fetch(`/invoices/${invoiceId}`, {
                    headers: { "Accept": "application/json" }
                });

                if (!response.ok) throw new Error('فشل في جلب البيانات');

                return await response.json();
            } catch (error) {
                console.error("Error:", error);
                const container = document.getElementById('receipt-container');
                if (container) {
                    container.innerHTML = `
                        <div style="color: red; text-align: center; font-size: 18px;">
                            خطأ في تحميل بيانات الأمر
                        </div>
                    `;
                }
                return null;
            }
        }

        function populateReceiptData(invoiceData) {
    if (!invoiceData) return;

    const amount = parseFloat(invoiceData.amount).toFixed(2);
    const [pounds, piastres] = amount.split('.');
    const amountInWords = numberToArabicWords(invoiceData.amount);

    // تعبئة البيانات الأساسية
    document.getElementById('receipt-id').textContent = invoiceData.disbursement_order_sequence || "غير متوفر";
    document.getElementById('member-name').textContent = invoiceData.name || "غير متوفر";
    document.getElementById('piastres').textContent = toArabicDigits(piastres);
    document.getElementById('pounds').textContent = toArabicDigits(pounds);
    document.getElementById('amount-words').textContent = amountInWords;
    document.getElementById('invoice-number').textContent = invoiceData.invoice_number || "غير متوفر";
    document.getElementById('description').textContent = invoiceData.description || "غير متوفر";

    // تعبئة الفئة والتصنيف الفرعي
    const categoryElement = document.getElementById('category');
    const subcategoryElement = document.getElementById('subcategory');
    const subcategoryContainer = document.getElementById('subcategory-container');

    if (invoiceData.subcategory) {
        categoryElement.textContent = invoiceData.category || "غير محدد";
        subcategoryElement.textContent = invoiceData.subcategory;
        subcategoryContainer.style.display = 'inline';
    } else {
        categoryElement.textContent = invoiceData.category || "غير محدد";
        subcategoryContainer.style.display = 'none';
    }
}

        function printReceipt() {
            window.print();
        }

        // تحميل البيانات عند فتح الصفحة
        document.addEventListener('DOMContentLoaded', async () => {
            updateCurrentDate();
            const invoiceData = await fetchInvoiceData();
            if (invoiceData) {
                populateReceiptData(invoiceData);
            }
        });
    </script>
</body>
</html>
