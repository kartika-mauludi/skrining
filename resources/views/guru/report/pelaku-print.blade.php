<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Pelaku</title>

    <style>
        @page {
            margin: 15px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        h1, h2, h3, h4, h5 {
            margin: 0;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .section {
            margin-bottom: 8px;
        }

        .box-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .border {
            border: 1px solid #000;
        }

        .box {
            border: 1px solid #000;
            padding: 6px;
            margin-bottom: 6px;
        }

        .header-line {
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 3px;
            vertical-align: top;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
        }

        .col-50 {
            width: 50%;
            float: left;
        }

        .clearfix {
            clear: both;
        }

        .chart {
            width: 100%;
            height: 200px;
            position: relative;
        }

        .chart canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<body>

    @include('guru.report.partials.report-content-pelaku')

</body>
</html>

<script>
window.onload = function () {

    setTimeout(function () {
        window.print();
    }, 500);

    window.onafterprint = function () {
        window.close();
    };

};
</script>