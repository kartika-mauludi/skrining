<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Korban</title>

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

    @include('guru.report.partials.report-content-korban')

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@0.5.7/chartjs-plugin-annotation.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-gauge@0.3.0/dist/chartjs-gauge.min.js"></script>
<script src="https://unpkg.com/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.js"></script>
<script> 
    const history = document.getElementById('history-chart-{{ $siswa->id }}').getContext('2d');
    const kategori = document.getElementById('kriteria-chart-{{ $siswa->id }}').getContext('2d'); 
    const cyber = document.getElementById('cyber-chart-{{ $siswa->id }}').getContext('2d');
    const gaugeChart = document.getElementById("gauge-chart-{{ $siswa->id }}").getContext("2d");

    const chartData = @json($skorKorbanAll);
    const chartData1 = @json($skorKorban);
    const chartData2 = @json($skorKorbanCyber);
    
    const colors = [
        '#3b82f6', // biru
        '#8b5cf6', // ungu
        '#ec4899'  // pink
    ];
    
    new Chart(history, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: chartData.datasets.map((ds, index) => ({
                label: ds.label,
                data: ds.data,
                borderColor: colors[index % colors.length],
                backgroundColor: colors[index % colors.length] + '33', // transparan
                pointBackgroundColor: colors[index % colors.length],
                borderWidth: 2,
                tension: 0.5,
                fill: false
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            annotation: {
                drawTime: 'beforeDatasetsDraw',
                annotations: [
                {
                    type: 'box',
                    yScaleID: 'y-axis-0',
                    yMin: 0,
                    yMax: 20,
                    backgroundColor: 'rgba(36, 198, 74, 0.2)',
                    borderWidth: 0
                },
                {
                    type: 'box',
                    yScaleID: 'y-axis-0',
                    yMin: 20,
                    yMax: 50,
                    backgroundColor: 'rgba(214, 242, 90, 0.2)',
                    borderWidth: 0
                },
                {
                    type: 'box',
                    yScaleID: 'y-axis-0',
                    yMin: 50,
                    yMax: 100,
                    backgroundColor: 'rgba(230, 34, 54, 0.2)',
                    borderWidth: 0
                }]
            },
            scales: {
                yAxes: [{
                    id: 'y-axis-0',
                    ticks: {
                        min: 0,
                        max: 100,
                        padding: 10,
                        callback: function(value) {
                            if (value === 20) return 'Aman';
                            if (value === 50) return 'Hati-hati';
                            if (value === 100) return 'Bahaya';
                            return '';
                        }
                    },
                    afterBuildTicks: function(scale) {
                        scale.ticks = [20, 50, 100];
                    }
                    }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            }
        },
    });

    new Chart(kategori, {
        type: 'bar',
        data: {
            labels: chartData1.labels,
            datasets: chartData1.datasets.map((ds, index) => ({
                label: ds.label,
                data: ds.data,
                backgroundColor: colors[index % colors.length] + '33', // transparan
                borderColor: colors[index % colors.length],
                borderWidth: 1
            }))
        },
        options: {
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                x: { offset: true },
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(cyber, {
        type: 'bar',
        data: {
            labels: chartData2.labels,
            datasets: chartData2.datasets.map((ds, index) => ({
                label: ds.label,
                data: ds.data,
                backgroundColor: colors[index % colors.length] + '33', // transparan
                borderColor: colors[index % colors.length],
                borderWidth: 1
            }))
        },
        options: {
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                x: { offset: true },
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(gaugeChart, {
        type: "gauge",
        data: {
            labels: ["Aman", "Hati Hati", "Bahaya"],
            datasets: [
            {
                data: [20, 50, 100],
                minValue: 0,
                maxValue: 100,
                value: @json($gaugeMeter),
                backgroundColor: ["#09e63cff", "#d4f544ff", "#cb3600ff"],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            layout: {
                padding: {
                    bottom: 35
                }
            },
            needle: {
                radiusPercentage: 2,
                widthPercentage: 2.2,
                lengthPercentage: 50,
                color: "#FF6112"
            },
            valueLabel: {
                display: true,
                formatter: (value) => {
                    return Math.round(value);
                },
                color: 'rgba(255, 255, 255, 1)',
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                borderRadius: 5,
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 12,
                    right: 12
                }
            },
            plugins: {
                datalabels: {
                    display: true,
                    formatter:  function (value, context) {
                        return context.chart.data.labels[context.dataIndex];
                    },
                    color: function (context) {
                        return context.dataset.backgroundColor;
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    borderRadius: 5,
                    padding: {
                        top: 3,
                        bottom: 3,
                        left: 5,
                        right: 5
                    },
                    font: {
                        size: 12,
                        weight: 600
                    }
                }
            }
        }
    });

    function generateHighResChart(type, data, options, width = 1400, height = 700) {
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = width;
        tempCanvas.height = height;

        const ctx = tempCanvas.getContext('2d');

        const tempChart = new Chart(ctx, {
            type: type,
            data: data,
            options: Object.assign({}, options, {
                responsive: false,
                animation: false,
                devicePixelRatio: 2
            })
        });

        const image = tempChart.toBase64Image('image/png', 1.0);
        tempChart.destroy();

        return image;
    }

    $('.btnExport').on('click', function () {
        const charts = Object.values(Chart.instances);
        const url    = $(this).data('url');

        charts.forEach(function(chart) {
            chart.options.devicePixelRatio = 2;
            chart.update();
        });

        setTimeout(function() {

            $('#history_image').val(Chart.instances[0].toBase64Image());
            $('#kategori_image').val(Chart.instances[1].toBase64Image());
            $('#cyber_image').val(Chart.instances[2].toBase64Image());
            $('#gauge_image').val(Chart.instances[3].toBase64Image());

            charts.forEach(function(chart) {
                chart.options.devicePixelRatio = 1;
                chart.update();
            });

            $('#formPrint').prop('action', url).trigger('submit');

        }, 300);
    });

</script>
