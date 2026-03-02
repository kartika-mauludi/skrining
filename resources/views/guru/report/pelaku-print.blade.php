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
            maintainAspectRatio: true,
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
            maintainAspectRatio: true,
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
            maintainAspectRatio: true,
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
            maintainAspectRatio: true,
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

    window.addEventListener('load', function () {
        setTimeout(() => {
            window.print();
        }, 900);
    });
</script>