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
                    <h4 class="mb-sm-0 font-size-18">Detail Final Result Checklist</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>Period</b></td>
                            <td class="align-middle">: {{ $checkjaringan->period }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b><i>Jaringan</i> Name</b></td>
                            <td class="align-middle">: {{ $checkjaringan->dealer_name.'( '.$checkjaringan->type.' )' }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Type Checklist</b></td>
                            <td class="align-middle">: {{ $checkjaringan->type_checklist }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header p-3">
                        <p class="text-center text-bold">{{ $checkjaringan->type_checklist }}</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered border-success nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="align-middle text-center">NO</th>
                                                <th class="align-middle text-center">POIN</th>
                                                <th class="align-middle text-center">EXIST GOOD</th>
                                                <th class="align-middle text-center">TOTAL POIN</th>
                                                <th class="align-middle text-center">RESULT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=0;
                                            @endphp
                                            @foreach($data as $item)
                                                @php
                                                    $no++;
                                                @endphp
                                                <tr>
                                                    <td class="align-middle text-center">
                                                        {{ $no }}
                                                    </th>
                                                    <td class="align-middle text-bold">
                                                        {{ $item->parent_point_checklist }}
                                                    </th>
                                                    <td class="align-middle text-center">
                                                        {{ $item->countTotalCheckedEG }}
                                                    </th>
                                                    <td class="align-middle text-center">
                                                        {{ $item->countTotalChecked }}
                                                    </th>
                                                    <td class="align-middle text-center">
                                                        {{ $item->resultPercentage }}%
                                                    </th>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2" class="align-middle text-center text-bold">
                                                    RESULT
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $countAllTotalCheckedEG }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $countAllTotalChecked }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $avgTotalResultPercentage }}%
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row justify-content-center mt-3">
                                <div class="col-12">
                                <hr>
                                    <div class="text-center">
                                        <figure class="highcharts-figure">
                                            <div id="container"></div>
                                        </figure>
                                    </div>
                                    <script>
                                        var typeparentValues = {!! json_encode($typeparentValues) !!};
                                        var categories = typeparentValues.map(function(value) {
                                            return value;
                                        });

                                        var dataGraph = {!! json_encode($dataGraph) !!};

                                        Highcharts.chart('container', {
                                            chart: {
                                                polar: true,
                                                type: 'line'
                                            },
                                            title: {
                                                text: '{{ $checkjaringan->type_checklist }}',
                                                x: -80
                                            },
                                            pane: {
                                                size: '80%'
                                            },
                                            xAxis: {
                                                categories: categories,
                                                tickmarkPlacement: 'on',
                                                lineWidth: 0
                                            },
                                            yAxis: {
                                                gridLineInterpolation: 'polygon',
                                                min: 0, 
                                                max: 120, 
                                                tickInterval: 10, 
                                                lineWidth: 0,
                                                labels: {
                                                    format: '{value}%', 
                                                    style: {
                                                        fontSize: '8px'
                                                    }
                                                },
                                                tickPositions: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110, 120]
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
                                                data: dataGraph,
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
        </div>




        {{-- <div class="row">
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
        </div> --}}

    </div>
</div>

@endsection