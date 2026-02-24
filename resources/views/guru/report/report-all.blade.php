<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report {{ $type }}</title>

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

        .col-33 {
            width: 33.333%;
            float: left;
        }

        .clearfix {
            clear: both;
        }

        img {
            max-width: 100%;
        }

        .chart {
            height: 150px;
            text-align: center;
        }
    </style>
</head>
<body>

    @foreach ($datas as $data)
        @if ($type == 'pelaku')
            @include('guru.report.partials.report-content-pelaku', $data)
        @elseif ($type == 'korban')
            @include('guru.report.partials.report-content-korban', $data)
        @endif

        <div style="page-break-after: always;"></div>
    @endforeach

</body>
<script>

window.onload = function() {
    setTimeout(function(){
        window.print();
    }, 1500);
}
</script>

</html>
