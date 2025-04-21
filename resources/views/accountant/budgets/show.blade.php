<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أمر التوريد</title>
    <style>
        /* تنسيق الخطوط والخلفية */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            line-height: 1.6;
            margin: 0;
        }
        /* تصميم الحاوية الرئيسية */
        .budget-container {
            display: block;
            margin-bottom: 20px;
        }
        /* تصميم تفاصيل أمر التوريد */
        .budget-details {
            border: 2px solid #ddd;
            padding: 30px;
            background-color: #fff;
            width: 100%;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            page-break-inside: avoid;
        }
        /* عنوان أمر التوريد */
        .budget-header {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 20px;
        }
        /* تفاصيل أمر التوريد */
        .budget-body {
            text-align: right;
            margin: 20px 0;
        }
        /* كل سطر في التفاصيل */
        .budget-line {
            margin-bottom: 20px;
            font-size: 20px;
            color: #2c3e50;
        }
        /* تاريخ اليوم */
        .date-line {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
            text-align: right;
        }
        /* توقيعات */
        .budget-footer {
            text-align: left;
            font-size: 16px;
            margin: 40px;
            font-weight: bold;
            color: #2c3e50;
        }
        .date-line {
    font-size: 20px; /* Increase the font size */
    color: #555;
    margin-bottom: 20px;
    text-align: right;
    font-weight: bold;
}

        /* زر الطباعة */
        .btn-print {
            margin-top: 30px;
            display: block;
            text-align: center;
            background-color: #007bff;
            color: #fff;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            border: none;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
        .dashed-line {
            border: none;
            border-top: 1px dashed #000;
            margin:  0;
        }
        /* جدول المبلغ */
        .amount-table {
            display: inline-table;
            text-align: center;
            margin: 0 10px;
            border-collapse: separate;
            border-spacing: 5px;
        }
        .amount-table th {
            font-weight: bold;
            padding: 5px;
        }
        .amount-table td {
            font-size: 22px;
            padding: 5px;
        }

       .association-name {
    font-size: 36px;
    font-weight: bold;
    color: #34495e;
    text-align: left;
    white-space: nowrap; /* ❗ Prevents text from wrapping */
    overflow: hidden;    /* Optional: Prevents overflow issues */
    text-overflow: ellipsis; /* Optional: Ellipsis if it's too long */
}

        @media print {
            .association-name {
        font-size: 20px;
        font-weight: bold;
        white-space: nowrap;
        text-align: left;
        direction: rtl;
    }
    body {
        background-color: #fff;
        font-size: 16px;
        padding: 20px; /* Padding around the entire page */
        margin: 0;
        border: 5px solid black; /* Border around the entire page */
    }

    .duplicate-container {
        display: flex;
        flex-direction: column;
        gap: 20px; /* Space between copies */
        padding: 20px; /* Padding inside the container */
    }

    .duplicate-item {
        page-break-inside: avoid; /* Prevent break within a single copy */
        margin: 0;
    }

    .budget-details {
        border: 2px solid #ddd; /* Border around the budget details */
        padding: 20px; /* Padding inside the budget container */
        font-size: 16px;
        margin-bottom: 10px; /* Space between the copies */
        box-shadow: none; /* Remove shadow for print */
    }
    .budget-header {
        font-size: 100px; /* Increase font size for printing */
        font-weight: bold;
    }


    .budget-line {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .date-line {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .budget-footer {
        font-size: 14px;
        margin-top: 20px;
    }

    .btn-print {
        display: none; /* Hide print button during print */
    }

    .dashed-line {
        border: none;
        border-top: 1px dashed #000;
        margin:  0;
    }

    @page {
        size: A4 portrait;
        margin: 10mm;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-3">
            <button class="btn-print" onclick="printBudget()">
                طباعة أمر التوريد
            </button>
        </div>
        <div id="budget-container" class="budget-container">
        </div>
    </div>
    <script>
        // 🟢 جلب بيانات أمر التوريد من API باستخدام `fetch`
        async function fetchBudgetData() {
            const budgetId = window.location.pathname.split('/').pop();
            try {
                const response = await fetch(`/budgets/${budgetId}`, {
                    headers: { "Accept": "application/json" }
                });
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`⚠️ فشل في جلب البيانات: ${errorText}`);
                }
                const budgetData = await response.json();
                populateBudgetData(budgetData);
            } catch (error) {
                console.error("❌ خطأ أثناء جلب البيانات:", error);
                document.getElementById('budget-container').innerHTML = `
                    <div style="color: red; font-size: 18px; text-align: center;">
                        ⚠️ فشل تحميل بيانات أمر التوريد. الرجاء التحقق من السيرفر.
                    </div>
                `;
            }
        }

        function populateBudgetData(budgetData) {
            if (!budgetData || !budgetData.tenant_name) {
                document.getElementById('budget-container').innerHTML = `
                    <div style="color: orange; font-size: 18px; text-align: center;">
                        ⚠️ لا توجد بيانات لهذا أمر التوريد.
                    </div>
                `;
                return;
            }

            // تقسيم المبلغ إلى جنيهات وقروش
            const amount = parseFloat(budgetData.amount).toFixed(2);
            const [pounds, piastres] = amount.split('.');
            const amountInWords = numberToArabicWords(budgetData.amount);

            // معالجة الفئة والتصنيف الفرعي
            const categoryDisplay = () => {
                if (!budgetData.category_name && !budgetData.parent_category_name) {
                    return 'غير محدد';
                }
                if (budgetData.parent_category_name && budgetData.category_name) {
                    return `${budgetData.parent_category_name}   ${budgetData.category_name}`;
                }
                if (budgetData.parent_category_name) {
                    return budgetData.parent_category_name;
                }
                return budgetData.category_name || 'غير محدد';
            };

            const budgetContainer = document.getElementById('budget-container');

            // إنشاء نسختين من المستند داخل حاوية واحدة
            const budgetTemplate = `
                <div class="duplicate-container">
                    <!-- النسخة الأولى -->
                    <div class="duplicate-item">
                        ${generateBudgetHTML(budgetData, pounds, piastres, amountInWords, categoryDisplay)}
                    </div>
                    <!-- النسخة الثانية -->
                    <div class="duplicate-item">
                        ${generateBudgetHTML(budgetData, pounds, piastres, amountInWords, categoryDisplay)}
                    </div>
                </div>
            `;
            budgetContainer.innerHTML = budgetTemplate;
            updateCurrentDate();
        }

        // دالة منفصلة لإنشاء HTML لكل نسخة
        function generateBudgetHTML(budgetData, pounds, piastres, amountInWords, categoryDisplay) {
            return `
                <div class="budget-details">
                    <!-- ✅ اللوجو -->
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <!-- Left Side: Logo -->
                       <div class="association-name">
    رابطة الحقوقين باسيوط
</div>

                        <div style="flex: 3; text-align: center; font-size: 26px; font-weight: bold; color: #34495e;">
                            <div>أمر التوريد </div>
                            <div> ${toArabicDigits(budgetData.supply_order_sequence)} / no </div>
                        </div>
                        <div style="flex: 1; text-align: right; padding-right: 10px;">
                            <img src="{{ URL::asset('assets/images/logo.jpeg') }}" alt="الشعار" style="height: 130px;">
                        </div>
                    </div>
                    <div class="budget-body">
                        <div class="budget-line">
                            وارد من السيد / ${budgetData.tenant_name}
                        </div>
                        <div class="budget-line">
                            <strong>المبلغ /</strong>
                            <table class="amount-table">
                                <thead>
                                    <tr>
                                        <th>قرش</th>
                                        <th>جنيه</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${toArabicDigits(piastres)}</td>
                                        <td>${toArabicDigits(pounds)}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <span style="margin-right: 10px;">${amountInWords}</span>
                        </div>
                        <div class="budget-line">
                            وذالك قيمة  /  ${categoryDisplay()} / ${budgetData.notes || "لا توجد ملاحظات"}
                        </div>
                        <div class="date-line">
                            <span class="current-date">تحرير في  / ${toArabicDigitsIndian(new Date().toLocaleDateString('ar-EG'))}</span>
                        </div>
                    </div>
                    <div class="budget-footer">
                        <span>امين خزينه</span>
                    </div>
                    <hr class="dashed-line">

                </div>
            `;
        }

        // 🟢 تحديث تاريخ اليوم
        function updateCurrentDate() {
            const today = new Date();
            const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
            const formattedDate = today.toLocaleDateString('ar-EG', options);
            const indianDate = toArabicDigitsIndian(formattedDate);
            document.querySelectorAll('.current-date').forEach(el => {
                el.textContent = `تحرير في  / ${indianDate}`;
            });
        }

        function toArabicDigitsIndian(str) {
            const arabicIndianNumerals = {
                '0': '٠', '1': '١', '2': '٢', '3': '٣', '4': '٤',
                '5': '٥', '6': '٦', '7': '٧', '8': '٨', '9': '٩'
            };
            return str.replace(/[0-9]/g, match => arabicIndianNumerals[match] || match);
        }

        // Function to increment supply_order_sequence and print budget
        async function printBudget() {
            try {
                await incrementSupplyOrderSequence();
                window.print();
            } catch (error) {
                console.error("Error incrementing supply order sequence:", error);
                alert("فشل تحديث الرقم التسلسلي.");
            }
        }

        async function incrementSupplyOrderSequence() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const budgetId = window.location.pathname.split('/').pop();

        const response = await fetch('/increment-supply-order-sequence', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ budget_id: budgetId })
        });

        if (!response.ok) {
            throw new Error('Failed to increment supply order sequence');
        }
    } catch (error) {
        console.error("Error:", error);
        throw error;
    }
}


        // 🟢 تحميل البيانات عند تشغيل الصفحة
        document.addEventListener('DOMContentLoaded', () => {
            fetchBudgetData();
        });

        // Convert digits to Arabic
        function toArabicDigits(str) {
            const western = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            const eastern = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            return str.toString().split('').map(c => western.includes(c) ? eastern[c] : c).join('');
        }

        // Convert number to Arabic words
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
    </script>
</body>
</html>
