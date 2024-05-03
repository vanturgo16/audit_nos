@extends('layouts.master')

@section('konten')

<style>
.highcharts-figure,
.highcharts-data-table table {
    min-width: 320px;
    max-width: 700px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <div class="text-center">
                                    <h5>Welcome to the "Audit Dashboard NOS"</h5>
                                    <p class="text-muted">Here you can manage NOS (Network Operational Standard) in PT Mitra Sendang Kemakmuran Banten Regional</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th class="align-middle text-center">Indikator</th>
                                        <th class="align-middle text-center">H1 PREMISES</th>
                                        <th class="align-middle text-center">H1 PEOPLE</th>
                                        <th class="align-middle text-center">H1 PROSES</th>
                                        <th class="align-middle text-center">H23 PREMISES</th>
                                        <th class="align-middle text-center">H1 PEOPLE</th>
                                        <th class="align-middle text-center">H23 PROSES</th>
                                        <th class="align-middle text-center">FINAL RESULT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!-- <td>Result Summary</td> -->
                                        <td>% Result</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                        <td>10%</td>
                                    </tr>
                                    <tr>
                                        <!-- <td>Result Summary</td> -->
                                        <td>Status</td>
                                        <td>Complete</td>
                                        <td>Complete</td>
                                        <td>Complete</td>
                                        <td>Complete</td>
                                        <td>Complete</td>
                                        <td>Complete</td>
                                        <td>null</td>
                                    </tr>
                                </tbody>
                            </table>
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                            <hr>
                                <div class="text-center">
                                    <figure class="highcharts-figure">
                                        <div id="container"></div>
                                        <!-- <p class="highcharts-description">
                                            A spiderweb chart or radar chart is a variant of the polar chart.
                                            Spiderweb charts are commonly used to compare multivariate data sets,
                                            like this demo using six variables of comparison.
                                        </p> -->
                                    </figure>

                                </div>
                                <script>
                                    Highcharts.chart('container', {
                                        chart: {
                                            polar: true,
                                            type: 'line'
                                        },

                                        title: {
                                            text: 'Final Result',
                                            x: -80
                                        },

                                        pane: {
                                            size: '80%'
                                        },

                                        xAxis: {
                                            categories: ['H1 PREMISES', 'H1 PEOPLE', 'H1 PROSES', 'H23 PREMISES', 'H23 PEOPLE', 'H23 PROSES'],
                                            tickmarkPlacement: 'on',
                                            lineWidth: 0
                                        },

                                        yAxis: {
                                            gridLineInterpolation: 'polygon',
                                            min: 0, 
                                            max: 100, 
                                            tickInterval: 10, 
                                            lineWidth: 0,
                                            labels: {
                                                format: '{value}%', 
                                                style: {
                                                    fontSize: '8px'
                                                }
                                            },
                                            tickPositions: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100]
                                        },

                                        tooltip: {
                                            shared: true,
                                            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y}%</b><br/>'
                                        },

                                        legend: {
                                            align: 'right',
                                            verticalAlign: 'middle',
                                            layout: 'vertical'
                                        },

                                        series: [{
                                            name: 'Nilai',
                                            data: [10, 60, 70, 80, 75, 90],
                                            pointPlacement: 'on',
                                            color: 'orange',
                                            marker: {
                                                enabled: true,
                                                symbol: 'circle', 
                                                radius: 3, 
                                                fillColor: 'red', 
                                                lineWidth: 3, 
                                                lineColor: null 
                                            }
                                        }],

                                        responsive: {
                                            rules: [{
                                                condition: {
                                                    maxWidth: 500
                                                },
                                                chartOptions: {
                                                    legend: {
                                                        align: 'center',
                                                        verticalAlign: 'bottom',
                                                        layout: 'horizontal'
                                                    },
                                                    pane: {
                                                        size: '70%'
                                                    }
                                                }
                                            }]
                                        }
                                    });
                                </script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center mt-3">
                            <div class="col-lg-6 col-md-12">
                                <h6><b>H1 PREMISES</b></h6>
                                <table class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">No</th>
                                            <th class="align-middle text-center">POIN</th>
                                            <th class="align-middle text-center">EXIST GOOD</th>
                                            <th class="align-middle text-center">TOTAL POIN</th>
                                            <th class="align-middle text-center">RESULT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Kebersihan Network</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Approval Layout New VinCi</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Eksterior</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Material Promosi Eksterior</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Parking Area</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Interior</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <hr>
                                <figure class="highcharts-figure">
                                    <div id="container1"></div>
                                    <!-- <p class="highcharts-description">
                                        A spiderweb chart or radar chart is a variant of the polar chart.
                                        Spiderweb charts are commonly used to compare multivariate data sets,
                                        like this demo using six variables of comparison.
                                    </p> -->
                                </figure>
                                <script>
                                    Highcharts.chart('container1', {
                                        chart: {
                                            polar: true,
                                            type: 'line'
                                        },

                                        title: {
                                            text: 'H1 PREMISES',
                                            x: -80
                                        },

                                        pane: {
                                            size: '80%'
                                        },

                                        xAxis: {
                                            categories: ['Kebersihan Network', 'Approval Layout New VinCi', 'Eksterior', 'Material Promosi Eksterior', 'Parking Area', 'Interior'],
                                            tickmarkPlacement: 'on',
                                            lineWidth: 0,
                                            labels: {
                                                style: {
                                                    fontSize: '10px' // Setel ukuran font di sini
                                                }
                                            }
                                        },

                                        yAxis: {
                                            gridLineInterpolation: 'polygon',
                                            min: 0, 
                                            max: 100, 
                                            tickInterval: 10, 
                                            lineWidth: 0,
                                            labels: {
                                                format: '{value}%', 
                                                style: {
                                                    fontSize: '8px'
                                                }
                                            },
                                            tickPositions: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100]
                                        },

                                        tooltip: {
                                            shared: true,
                                            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y}%</b><br/>'
                                        },

                                        legend: {
                                            align: 'right',
                                            verticalAlign: 'middle',
                                            layout: 'vertical'
                                        },

                                        series: [{
                                            name: 'Result',
                                            data: [80, 80, 80, 80, 70, 60],
                                            pointPlacement: 'on',
                                            color: 'orange'
                                        }],

                                        responsive: {
                                            rules: [{
                                                condition: {
                                                    maxWidth: 500
                                                },
                                                chartOptions: {
                                                    legend: {
                                                        align: 'center',
                                                        verticalAlign: 'bottom',
                                                        layout: 'horizontal'
                                                    },
                                                    pane: {
                                                        size: '70%'
                                                    }
                                                }
                                            }]
                                        }
                                    });
                                </script>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center mt-3">
                            <div class="col-lg-6 col-md-12">
                                <h6><b>H1 PEOPLE</b></h6>
                                <table class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">No</th>
                                            <th class="align-middle text-center">POIN</th>
                                            <th class="align-middle text-center">EXIST GOOD</th>
                                            <th class="align-middle text-center">TOTAL POIN</th>
                                            <th class="align-middle text-center">RESULT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Kebersihan Network</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Approval Layout New VinCi</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Eksterior</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Material Promosi Eksterior</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Parking Area</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Interior</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td>100%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <hr>
                                <figure class="highcharts-figure">
                                    <div id="container2"></div>
                                    <!-- <p class="highcharts-description">
                                        A spiderweb chart or radar chart is a variant of the polar chart.
                                        Spiderweb charts are commonly used to compare multivariate data sets,
                                        like this demo using six variables of comparison.
                                    </p> -->
                                </figure>
                                <script>
                                    Highcharts.chart('container2', {
                                        chart: {
                                            polar: true,
                                            type: 'line'
                                        },

                                        title: {
                                            text: 'H1 PEOPLE',
                                            x: -80
                                        },

                                        pane: {
                                            size: '80%'
                                        },

                                        xAxis: {
                                            categories: ['Kebersihan Network', 'Approval Layout New VinCi', 'Eksterior', 'Material Promosi Eksterior', 'Parking Area', 'Interior'],
                                            tickmarkPlacement: 'on',
                                            lineWidth: 0,
                                            labels: {
                                                style: {
                                                    fontSize: '10px' // Setel ukuran font di sini
                                                }
                                            }
                                        },

                                        yAxis: {
                                            gridLineInterpolation: 'polygon',
                                            min: 0, 
                                            max: 100, 
                                            tickInterval: 10, 
                                            lineWidth: 0,
                                            labels: {
                                                format: '{value}%', 
                                                style: {
                                                    fontSize: '8px'
                                                }
                                            },
                                            tickPositions: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100]
                                        },

                                        tooltip: {
                                            shared: true,
                                            pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y}%</b><br/>'
                                        },

                                        legend: {
                                            align: 'right',
                                            verticalAlign: 'middle',
                                            layout: 'vertical'
                                        },

                                        series: [{
                                            name: 'Result',
                                            data: [80, 80, 80, 80, 70, 60],
                                            pointPlacement: 'on',
                                            color: 'orange'
                                        }],

                                        responsive: {
                                            rules: [{
                                                condition: {
                                                    maxWidth: 500
                                                },
                                                chartOptions: {
                                                    legend: {
                                                        align: 'center',
                                                        verticalAlign: 'bottom',
                                                        layout: 'horizontal'
                                                    },
                                                    pane: {
                                                        size: '70%'
                                                    }
                                                }
                                            }]
                                        }
                                    });
                                </script>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection