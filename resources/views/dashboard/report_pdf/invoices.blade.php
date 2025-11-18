<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة مبيعات ضريبية</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-title {
            color: #4A90C8;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .company-details {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
        }

        .logo-section {
            display: flex;
            align-items: flex-start;
            margin: 30px 0;
            gap: 20px;
        }

        .logo {
            height: 5rem;
        }

        .logo img {
            width: 100%;
            height: 100%;
        }

        .company-name-ar {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .company-name-en {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .invoice-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #4A90C8;
            margin: 30px 0;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .info-item {
            display: flex;
            gap: 10px;
        }

        .info-label {
            font-weight: bold;
        }

        .client-info {
            display: flex;
            justify-content: space-between;
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .client-label {
            font-weight: bold;
            font-size: 16px;
        }

        .client-value {
            font-size: 16px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            background-color: #4A90C8;
            color: white;
            padding: 12px;
            text-align: center;
            font-size: 16px;
            border: 1px solid #ddd;
        }

        td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 15px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .summary-section {
            margin: 30px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 16px;
            color: #4A90C8;
            border-top: 2px solid #4A90C8;
            padding-top: 15px;
        }

        .notes-section {
            margin: 30px 0;
            text-align: center;
        }

        .notes-title {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 10px;
            color: #4A90C8;
        }

        .notes-content {
            font-size: 13px;
            line-height: 1.6;
            color: #333;
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
        }

        .qr-code {
            width: 150px;
            height: 150px;
            background-color: #000;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-placeholder {
            width: 140px;
            height: 140px;
            background: repeating-linear-gradient(
                0deg,
                #000 0px,
                #000 2px,
                #fff 2px,
                #fff 4px
            ),
            repeating-linear-gradient(
                90deg,
                #000 0px,
                #000 2px,
                #fff 2px,
                #fff 4px
            );
        }

        .footer-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }

        .footer-text {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .footer-company {
            font-size: 11px;
            color: #4A90C8;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-title">شركة الشقران للتكييف والاعمال الكهروميكانيكية والمقاولات</div>
            <div class="company-details">
                الرياض، حي الملقا - طريق الملك فهد بن عبدالعزيز - هاتف: 0112578000 تحويلة 114<br>
                سجل تجاري: 1010185691 - رقم ضريبي: 300837314900003
            </div>
        </div>

        <div class="logo-section">
            <div class="logo">
                <img src="{{ asset('dashboard/assets/img/brand/desktop-logo.png') }}" alt="">
            </div>
        </div>

        <h1 class="invoice-title">فاتــورة مبيعــات ضريبية</h1>

        <div class="invoice-info">
            <div class="info-item">
                <span class="info-label">رقم الفاتورة:</span>
                <span>INV2178</span>
            </div>
            <div class="info-item">
                <span class="info-label">تاريخ الفاتورة:</span>
                <span>20-01-2025</span>
            </div>
            <div class="info-item">
                <span class="info-label">الحالة:</span>
                <span>موافق عليه</span>
            </div>
        </div>

        <div class="client-info">
            <div>
                <span class="client-label">اسم العميل:</span>
                <span class="client-value"> شركة المطاعم الاهلية</span>
            </div>
            <div>
                <span class="client-label">الرقم الضريبي للعميل:</span>
                <span class="client-value"> 300056145400003</span>
            </div>
        </div>

        <div class="client-info">
            <span class="client-value">العنوان: شارع التخصصي، الرياض، الرياض، السعودية، 12334</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>م</th>
                    <th>الصنف</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الخصم</th>
                    <th>الضريبة</th>
                    <th>السعر الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>اعمال تكاليف</td>
                    <td>1</td>
                    <td>10,950.00</td>
                    <td>0.00 ريس</td>
                    <td>1,642.50 ريس</td>
                    <td>12,592.50 ريس</td>
                </tr>
            </tbody>
        </table>

        <div class="summary-section">
            <div class="summary-row">
                <span>الإجمالي غير شامل الضريبة: 10,950.00 ريس</span>
            </div>
            <div class="summary-row total">
                <span>الضمان حسب سياسة وكيل الاجهزة</span>
            </div>
        </div>

        <div class="notes-section">
            <div class="notes-title">الموضح:</div>
            <div class="notes-content">
                اعمال تركيبات أقفلية -هاردير المدينةتفاصيل شراء رقم 442550<br>
                مجموع ضريبة القيمة المضافة 15%: 1,642.50 ريس<br>
                الإجمالي شامل الضريبة: 12,592.50 ريس
            </div>
        </div>

        <div class="qr-section">
            <div class="qr-code">
                <div class="qr-placeholder"></div>
            </div>
        </div>

        <div class="footer-section">
            <div class="footer-text">للتحويل على حسابنا في مصرف الراجحي</div>
            <div class="footer-text">رقم الايبان: SA3580000369608010168669</div>
            <div class="footer-company">شركة الشقران للتكييف والاعمال الكهروميكانيكية والمقاولات</div>
        </div>
    </div>
</body>
</html>