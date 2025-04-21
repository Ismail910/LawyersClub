<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إيصال الدفع</title>
    <style>
        /* تنسيق الخطوط والخلفية */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            line-height: 1.6;
            margin: 0;
        }

        /* تصميم الإيصال */
        .receipt-container {
            border: 2px solid #ddd;
            padding: 30px;
            background-color: #fff;
            width: 100%;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        /* عنوان الإيصال */
        .receipt-header {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 20px;
        }

        /* تفاصيل الإيصال */
        .receipt-body {
            text-align: right;
            margin: 20px 0;
        }

        /* كل سطر في التفاصيل */
        .receipt-line {
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

        .receipt-footer {
            font-size: 16px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            color: #555;
        }

        .duplicate-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            /* المسافة بين النسختين */
            border: 2px solid #000;
            padding: 20px;
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
                padding: 20px;
                /* Add padding around the entire page */
                margin: 0;
                border: 5px solid black;
                /* Border around the entire page */
            }

            .duplicate-container {
                display: flex;
                flex-direction: column;
                gap: 20px;
                /* Space between the copies */
                padding: 20px;
                /* Padding inside the duplicate container */
                border: 2px solid #000;
                page-break-inside: avoid;
            }

            .receipt-container {
                page-break-inside: avoid;
                margin: 0;
                padding: 20px;
                /* Padding inside each receipt container */
                font-size: 16px;
                border: 2px solid black;
                /* Border around the receipt container */
            }

            .receipt-header {
                font-size: 20px;
                margin-bottom: 10px;
            }

            .receipt-line {
                font-size: 16px;
                margin-bottom: 10px;
            }

            .date-line {
                font-size: 14px;
                margin-bottom: 10px;
            }

            .receipt-footer {
                font-size: 14px;
                margin-top: 20px;
            }

            .btn-print {
                display: none;
                /* Hide print button during print */
            }

            .receipt-container {
                margin-bottom: 20px;
                /* Space between receipts when printing */
            }
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
            <button class="btn-print" onclick="printReceipt()">
                طباعة الإيصال
            </button>
        </div>

        <!-- النسخة الأولى -->
        <div id="receipt-container" class="receipt-container">

            <!-- شعار -->
            <div style="display: flex; justify-content: center; align-items: center;">
                <div class="association-name">
                    رابطة الحقوقين باسيوط
                </div>



                <!-- Centered Text (Order Number) -->
                <div style="flex: 3; text-align: center; font-size: 26px; font-weight: bold; color: #34495e;">
                    <div class="receipt-header">إيصال تحصيل نقدي</div>
                    <div class="receipt-header">no / <span id="receipt-id">جاري تحميل البيانات...</span></div>
                </div>

                <div style="flex: 1; text-align: right; padding-right: 10px;">
                    <img src="{{ URL::asset('assets/images/logo.jpeg') }}" alt="الشعار" style="height: 150px;">
                </div>


            </div>

            <!-- تفاصيل الإيصال -->
            <div class="receipt-body" style="direction: rtl;">
                <div class="receipt-line">
                    وصل من السيد / <span id="member-name">جاري تحميل البيانات...</span>
                </div>

                <div class="receipt-line">
                    <span style="font-weight: bold;">المبلغ:</span>
                    <span id="amount-numbers" style="margin: 0 10px;"></span>
                    <span id="amount-words"></span>
                </div>

                <div class="receipt-line">
                    وذلك قيمة : <span id="notes">جاري تحميل البيانات...</span>
                </div>
            </div>

            <div>وهذا إيصال منا بذلك</div>

            <div class="date-line" style="text-align: right;">
                <span id="current-date">جاري تحميل التاريخ...</span>
            </div>

            <div class="receipt-footer">
                <span>أمين الخزينة</span>
                <span> يعنمد أمين الصندوق </span>
            </div>
        </div>

        <!-- النسخة الثانية -->
        <div id="receipt-container-copy" class="receipt-container">

            <!-- شعار -->
            <div style="display: flex; justify-content: center; align-items: center; ;">
                <div style="flex: 1; text-align: left; padding-left: 10px;">
                    رابطة الحقوقين باسيوط</div>

                <!-- Left Side: Logo -->


                <!-- Centered Text (Receipt Title and Order Number) -->
                <div style="flex: 3; text-align: center; font-size: 26px; font-weight: bold; color: #34495e;">
                    <div class="receipt-header">إيصال تحصيل نقدي</div>
                    <div class="receipt-header">no / <span id="receipt-id-copy">جاري تحميل البيانات...</span></div>
                </div>

                <!-- Right Side (Empty or another content) -->
                <div style="flex: 1; text-align: right; padding-right: 10px;">
                    <img src="{{ URL::asset('assets/images/logo.jpeg') }}" alt="الشعار" style="height: 150px;">
                </div>
            </div>



            <!-- تفاصيل الإيصال -->
            <div class="receipt-body" style="direction: rtl;">
                <div class="receipt-line">
                    وصل من السيد / <span id="member-name-copy">جاري تحميل البيانات...</span>
                </div>

                <div class="receipt-line">
                    <span style="font-weight: bold;">المبلغ:</span>
                    <span id="amount-copy-numbers" style="margin: 0 10px;"></span>
                    <span id="amount-copy-words"></span>
                </div>

                <div class="receipt-line">
                    وذلك قيمة : <span id="notes-copy">جاري تحميل البيانات...</span>
                </div>
            </div>

            <div>وهذا إيصال منا بذلك</div>

            <div class="date-line" style="text-align: right;">
                <span id="current-date-copy">جاري تحميل التاريخ...</span>
            </div>



            <div class="receipt-footer">
                <span>أمين الخزينة</span>
                <span> يعنمد أمين الصندوق </span>
            </div>
        </div>
    </div>
</body>

<script>
    // Fetch member data
    async function fetchMemberData() {
        const memberId = window.location.pathname.split('/').pop(); // Extract ID from URL
        try {
            const response = await fetch(`/hr/members/${memberId}`, {
                headers: {
                    "Accept": "application/json" // Enforce JSON response from the server
                }
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`Failed to fetch data: ${errorText}`);
            }

            const memberData = await response.json();
            populateReceiptData(memberData, memberId);
        } catch (error) {
            console.error("Error fetching data:", error);
            document.getElementById('receipt-container').innerHTML = `
                    <div style="color: red; font-size: 18px; text-align: center;">
                        فشل تحميل بيانات العضو.
                    </div>
                `;
        }
    }
    function populateReceiptData(memberData, memberId) {
        if (!memberData || !memberData.name || !memberData.payment_voucher_number || !memberData.notes || memberData.member_subscription_sequence === undefined) {
            document.getElementById('receipt-container').innerHTML = `
            <div style="color: orange; font-size: 18px; text-align: center;">
                ⚠️ لا توجد بيانات لهذا العضو.
            </div>
        `;
            return;
        }

        // Populate receipt with member data
        document.getElementById('receipt-id').textContent = memberData.member_subscription_sequence || "غير متوفر";
        document.getElementById('member-name').textContent = memberData.name || "غير متوفر";

        let rawAmount = memberData.amount || "0";
        rawAmount = rawAmount.replace(/,/g, '');
        const amount = parseFloat(rawAmount).toFixed(2);
        const [pounds, piastres] = amount.split('.');

        const poundsArabic = toArabicDigits(pounds);
        const piastresArabic = toArabicDigits(piastres);
        const amountWords = numberToArabicWords(amount);

        // النسخة الثانية
        document.getElementById('amount-copy-words').textContent = `${amountWords}`;

        // عرض "قرش" و "جنيه" مع مسافة كبيرة بينهما
        document.getElementById('amount-copy-numbers').innerHTML = `
    <table style="display: inline-block; text-align: center; font-size: 16px; margin:0 auto; border-collapse: separate; border-spacing: 5px 5px;">
        <thead>
            <tr>
                <th style="font-weight: bold; padding: 5px;">قرش</th>
                <th style="font-weight: bold; padding: 5px;">جنيه</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size: 22px; padding: 5px; ">${toArabicDigits(piastres)}</td>
                <td style="font-size: 22px; padding: 5px;  ">${toArabicDigits(pounds)}</td>
            </tr>
        </tbody>
    </table>
`;
        document.getElementById('amount-numbers').innerHTML = `
    <table style="display: inline-block; text-align: center; font-size: 16px; margin:0 auto; border-collapse: separate; border-spacing: 5px 5px;">
        <thead>
            <tr>
                <th style="font-weight: bold; padding: 5px;">قرش</th>
                <th style="font-weight: bold; padding: 5px;">جنيه</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size: 22px; padding: 5px; ">${toArabicDigits(piastres)}</td>
                <td style="font-size: 22px; padding: 5px;  ">${toArabicDigits(pounds)}</td>
            </tr>
        </tbody>
    </table>
`;

        document.getElementById('amount-words').textContent = `${amountWords}`;

        document.getElementById('notes').textContent = memberData.notes || "غير متوفر";

        // Populate the copy
        document.getElementById('receipt-id-copy').textContent = memberData.member_subscription_sequence || "غير متوفر";
        document.getElementById('member-name-copy').textContent = memberData.name || "غير متوفر";
        document.getElementById('notes-copy').textContent = memberData.notes || "غير متوفر";
    }



    // Load member data on page load
    document.addEventListener('DOMContentLoaded', () => {
        fetchMemberData();
        updateCurrentDate();
    });

    // Update current date
    function updateCurrentDate() {
        const today = new Date();
        const options = { year: 'numeric', month: 'numeric', day: 'numeric' };

        // Format the date
        const formattedDate = today.toLocaleDateString('ar-EG', options);

        // Convert the formatted date to Indian Arabic numerals
        const indianDate = toArabicDigitsIndian(formattedDate);

        // Set the formatted date in the elements
        document.getElementById('current-date').textContent = `تحرير في   : ${indianDate}`;
        document.getElementById('current-date-copy').textContent = `تحرير في   : ${indianDate}`;
    }

    function toArabicDigitsIndian(str) {
        const arabicIndianNumerals = {
            '0': '٠',
            '1': '١',
            '2': '٢',
            '3': '٣',
            '4': '٤',
            '5': '٥',
            '6': '٦',
            '7': '٧',
            '8': '٨',
            '9': '٩',
        };

        return str.replace(/[0-9]/g, match => arabicIndianNumerals[match] || match);
    }


    // Increment member_subscription_sequence and print receipt
    async function printReceipt() {
    try {
        const memberId = window.location.pathname.split('/').pop();
        await incrementMemberSubscriptionSequence(memberId);

        window.print();
    } catch (error) {
        console.error("Error incrementing member subscription sequence:", error);
    }
}

    async function incrementMemberSubscriptionSequence(memberId) {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const response = await fetch('/increment-member-subscription-sequence', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                member_id: memberId
            })
        });

        if (!response.ok) {
            throw new Error('Failed to increment member subscription sequence');
        }
    } catch (error) {
        console.error("Error:", error);
        alert("فشل تحديث الرقم التسلسلي.");
    }
}


</script>

<script>
    function numberToArabicWords(number) {
        const ones = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة'];
        const teens = ['عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر', 'سبعة عشر', 'ثمانية عشر', 'تسعة عشر'];
        const tens = ['', 'عشرة', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];
        const hundreds = ['', 'مائة', 'مائتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة', 'تسعمائة'];

        function convertBelow1000(n) {
            let word = '';

            if (n >= 100) {
                const h = Math.floor(n / 100);
                word += hundreds[h];
                n %= 100;
                if (n) word += ' و ';
            }

            if (n >= 20) {
                const t = Math.floor(n / 10);
                const u = n % 10;
                word += u ? `${ones[u]} و ${tens[t]}` : tens[t];
            } else if (n >= 10) {
                word += teens[n - 10];
            } else if (n > 0) {
                word += ones[n];
            }

            return word;
        }

        number = parseFloat(number).toFixed(2);
        const [poundStr, piasterStr] = number.split('.').map(n => parseInt(n));

        let result = '';
        let thousands = Math.floor(poundStr / 1000);
        let rest = poundStr % 1000;

        if (thousands > 0) {
            result += `${convertBelow1000(thousands)} ألف`;
            if (rest > 0) result += ' و ';
        }

        if (rest > 0) {
            result += `${convertBelow1000(rest)}`;
        }

        if (poundStr > 0) result += ' جنيه';

        if (piasterStr > 0) {
            if (poundStr > 0) result += ' و ';
            result += `${convertBelow1000(piasterStr)} قرش`;
        }

        if (!poundStr && !piasterStr) {
            result = 'صفر جنيه';
        }

        return result + ' فقط لا غير';
    }

    function toArabicDigits(str) {
        const westernToArabic = {
            '0': '٠',
            '1': '١',
            '2': '٢',
            '3': '٣',
            '4': '٤',
            '5': '٥',
            '6': '٦',
            '7': '٧',
            '8': '٨',
            '9': '٩',
            ',': ','
        };
        return str.toString().split('').map(d => westernToArabic[d] ?? d).join('');
    }

</script>



</html>
