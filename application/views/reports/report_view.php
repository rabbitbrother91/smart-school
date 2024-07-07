<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url(<?php echo base_url('application/fonts/DejaVuSans.ttf'); ?>);
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            text-align: center;
            direction: rtl;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        h2 {
            margin-bottom: 5px;
        }

        .double-line {
            border-top: 3px solid black;
            margin: 5px 0;
            position: relative;
        }

        .double-line::after {
            content: "";
            display: block;
            border-top: 1px solid black;
            margin: 3px 0 0 0;
        }

        .info-table {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
            direction: rtl;
            border: none;
            /* Add this line to remove the border */
        }

        .info-table td {
            border: none;
            padding: 5px;
            text-align: right;
            font-size: 14px;
        }

        .info-table td:first-child {
            width: 15%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 12px;
            /* Increased padding for row height */
            text-align: center;
            position: relative;
            /* Ensure the pseudo-elements are positioned correctly */
            vertical-align: middle;
            /* Center text vertically */
        }

        th {
            background-color: #f2f2f2;
        }

        .signature {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
        }

        .logo {
            width: 100px;
            height: auto;
        }

        .content {
            line-height: 2;
            /* Increase line height for better spacing */
        }

        .no-border-table {
            border: none;
            border-collapse: collapse;
            margin-top: 20px;
            padding: 5px;
            margin-bottom: 20px;
            /* Added bottom margin */
        }

        .no-border-table td {
            border: none;
            padding: 5px;
            text-align: center;
            /* Center text in table cells */
        }

        .no-border-table td:first-child {
            text-align: right;
            /* Right-align first column content */
        }

        .content {
            width: 100%;
            border-collapse: collapse;
            /* Ensures borders are collapsed into a single border */
        }

        .content th,
        .content td {
            border: 1px solid black;
            /* Adds a solid black border around each cell */
            padding: 12px;
            /* Increased padding for cell content */
            text-align: center;
            /* Centers text within cells */
            height: 40px;
            /* Set a minimum height for each row */
            vertical-align: middle;
            /* Vertically center content within cells */
        }

        .content th {
            background-color: #f2f2f2;
            /* Adds a light gray background to header cells */
        }

        /* Adjust column widths as needed */
        .content th:nth-child(1),
        .content td:nth-child(1) {
            width: 20%;
        }

        /* مكان الازدياد */
        .content th:nth-child(2),
        .content td:nth-child(2) {
            width: 15%;
        }

        /* تاريخ */
        .content th:nth-child(3),
        .content td:nth-child(3) {
            width: 10%;
        }

        /* النوع */
        .content th:nth-child(4),
        .content td:nth-child(4) {
            width: 25%;
        }

        /* الاسم */
        .content th:nth-child(5),
        .content td:nth-child(5) {
            width: 15%;
        }

        /* الرمز */
        .content th:nth-child(6),
        .content td:nth-child(6) {
            width: 15%;
        }

        /* ر */
    </style>
</head>

<body>
    <div class="header">
        <!-- <img src="<?php echo base_url('assets/images/LOGOWIZ.PNG'); ?>" class="logo" alt="Logo"> -->
        <h2>لائحة التلاميذ</h2>
        <div class="double-line"></div>
    </div>

    <table class="no-border-table">
        <tr>
            <!-- academic year -->
            <td>السنة الدراسية: <?php echo '2020/2021'; ?></td>
            <!-- Community -->
            <td>الجماعة: <?php echo 'CCC'; ?></td>
            <!-- academy -->
            <td>الأكاديمية: <?php echo 'ACA'; ?></td>

        </tr>
        <tr>
            <td></td>
            <!-- Enterpries -->
            <td>المؤسسة: <?php echo 'SCO'; ?></td>

            <!-- Directorate -->
            <td>المديرية: <?php echo 'DDD'; ?></td>
        </tr>
        <tr>
            <td></td>
            <!-- level -->
            <td>المستوى: <?php echo $class; ?></td>
            <!-- Section -->
            <td>القسم: <?php echo $section; ?></td>

        </tr>
    </table>

    <table class="content">
        <thead>
            <tr>
                <!-- Place of birth -->
                <th>مكان الازدياد</th>
                <!-- Birthday -->
                <th>تاريخ</th>
                <!-- Gender -->
                <th>النوع</th>
                <!-- Name -->
                <th>الاسم</th>
                <!-- ID -->
                <th>الرمز</th>
                <!-- Index -->
                <th> ر</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $index => $student) : ?>
                <tr>
                    <td><?php echo $student->city; ?></td>
                    <td><?php echo $student->dob; ?></td>
                    <td><?php echo $student->gender; ?></td>
                    <td><?php echo $student->firstname; ?></td>
                    <td><?php echo $student->id; ?></td>
                    <td><?php echo $index + 1; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="signature">
        توقيع المدير:
    </div>
</body>

</html>