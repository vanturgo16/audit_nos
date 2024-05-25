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
                    <div class="card-header p-3">
                        <p class="text-center text-bold">Final Result Checklist</p>
                    </div>
                    <div class="card-body">
                        <div class="card py-3">
                            <form action="{{ route('dashboard') }}" id="formsearch" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row justify-content-center">
                                    @if(in_array(Auth::user()->role, ['Super Admin', 'Admin', 'Assessor Main Dealer', 'PIC NOS MD', 'PIC Dealers']))
                                        <div class="col-4">
                                            <label class="form-label">Jaringan</label>
                                            <select class="form-select js-example-basic-single" style="width: 100%" name="dealer_name" required>
                                                <option value="" selected>-- Select Jaringan --</option>
                                                @foreach ($dealers as $item)
                                                    <option value="{{ $item->id }}" @if($idDealer == $item->id) selected="selected" @endif>{{ $item->dealer_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-4">
                                        <label class="form-label">Period</label>
                                        <select class="form-select js-example-basic-single" style="width: 100%" name="id_period" required>
                                            <option value="" selected>-- Select Period --</option>
                                            @foreach ($periods as $item)
                                                <option value="{{ $item->id }}">{{ $item->period }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <script>
                                        var idPeriod = "{{ $idPeriod }}";
                                        if(idPeriod){
                                            var periodsList = {!! json_encode($periodslist) !!};
                                            $('select[name="id_period"]').empty();
                                            $('select[name="id_period"]').append(
                                                '<option value="" selected>-- Select Period --</option>'
                                            );

                                            $.each(periodsList, function(index, value) {
                                                var option = '<option value="' + value.id + '">' + value.period + '</option>';
                                                if (value.id == idPeriod) {
                                                    option = '<option value="' + value.id + '" selected>' + value.period + '</option>';
                                                }
                                                $('select[name="id_period"]').append(option);
                                            });
                                        }

                                        // getPeriodbyJaringan
                                        $('select[name="dealer_name"]').on('change', function() {
                                            var idDealer = $(this).val();
                                            var url = '{{ route("mapping.dealer", ":id") }}';
                                            url = url.replace(':id', idDealer);
                                            
                                            if (idDealer) {
                                                $.ajax({
                                                    url: url,
                                                    type: "GET",
                                                    dataType: "json",
                                                    success: function(data) {
                                                        $('select[name="id_period"]').empty();
                                                        $('select[name="id_period"]').append(
                                                            '<option value="" selected>-- Select Period --</option>'
                                                        );

                                                        $.each(data, function(div, value) {
                                                            $('select[name="id_period"]').append(
                                                                '<option value="' + value.id + '">' + value.period + '</option>');
                                                        });
                                                    }
                                                });
                                            } else {
                                                $('select[name="id_period"]').empty();
                                                $('select[name="id_period"]').append(
                                                    '<option value="" selected>-- Select Period --</option>'
                                                );
                                            }
                                        });
                                    </script>

                                    <div class="col-2">
                                        <label class="form-label">&nbsp;</label><br>
                                        <button id="src" type="submit" class="btn btn-primary waves-effect btn-label waves-light w-100">
                                            <i class="mdi mdi-magnify label-icon"></i> Search Result
                                        </button>                                
                                    </div>
                                </div>
                            </form>
                            <script>
                                document.getElementById('formsearch').addEventListener('submit', function(event) {
                                    if (!this.checkValidity()) {
                                        event.preventDefault(); // Prevent form submission if it's not valid
                                        return false;
                                    }
                                    var submitButton = this.querySelector('button[id="src"]');
                                    submitButton.disabled = true;
                                    submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                    return true; // Allow form submission
                                });
                            </script>
                        </div>

                        <hr>
                        <div class="row">
                            @if($statusPeriod == 'notselect')
                                <div class="col-12 d-flex justify-content-center align-items-center">
                                    <h5><span class="badge bg-secondary text-white">No data available</span></h5>
                                </div>
                            @elseif($statusPeriod == null)
                                <div class="col-12 d-flex justify-content-center align-items-center">
                                    <h5><span class="badge bg-warning text-white">Expired Period</span></h5>
                                </div>
                            @elseif($statusPeriod == 0)
                                <div class="col-12 d-flex justify-content-center align-items-center">
                                    <h5><span class="badge bg-warning text-white">Not yet assigned</span></h5>
                                </div>
                            @else
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered border-success nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" class="align-middle text-center">Indikator</th>
                                                    @foreach($typechecklist as $type)
                                                        <th class="align-middle text-center">
                                                            <a href="{{ route('dashboard.detailresult', encrypt($type->id_checklist_jaringan)) }}" target="blank"
                                                                type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
                                                                <i class="mdi mdi-information label-icon"></i>{{ $type->type_checklist }}
                                                            </a>
                                                        </th>
                                                    @endforeach
                                                    {{-- <th class="align-middle text-center">Final Result</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td rowspan="6" class="align-middle text-center text-bold">Result Summary</td>
                                                    <td>% Result</td>
                                                    @foreach($resultchecklist as $result)
                                                        @if(in_array($statusPeriod, [1,2]))
                                                            <td class="align-middle text-center"><span class="badge bg-secondary text-white">Not Yet Submit</span></td>
                                                        @else
                                                            <td class="align-middle text-center">{{ $result->result_percentage }}%</th>
                                                        @endif
                                                    @endforeach
                                                    {{-- <td></td> --}}
                                                </tr>
                                                <tr>
                                                    <td>Status</td>
                                                    @foreach($resultchecklist as $result)
                                                        <td class="align-middle text-center">
                                                            @if($result->status == "" || $result->status == null)
                                                                <span class="badge bg-secondary text-white">Not Started</span>
                                                            @elseif($result->status == 0)
                                                                <span class="badge bg-warning text-white">Not Complete</span>
                                                            @else
                                                                <span class="badge bg-success text-white">Complete</span>
                                                            @endif
                                                        </th>
                                                    @endforeach
                                                    {{-- <td></td> --}}
                                                </tr>
                                                <tr>
                                                    <td>Result Audit</td>
                                                    @foreach($resultchecklist as $result)
                                                        @if(in_array($statusPeriod, [1,2]))
                                                            <td class="align-middle text-center"><span class="badge bg-secondary text-white">Not Yet Submit</span></td>
                                                        @else
                                                            <td class="align-middle text-center">{{ $result->audit_result }}</th>
                                                        @endif
                                                    @endforeach
                                                    {{-- <td></td> --}}
                                                </tr>
                                                <tr>
                                                    <td>Mandatory Item</td>
                                                    @foreach($resultchecklist as $result)
                                                        @if(in_array($statusPeriod, [1,2]))
                                                            <td class="align-middle text-center"><span class="badge bg-secondary text-white">Not Yet Submit</span></td>
                                                        @else
                                                            <td class="align-middle text-center">{{ $result->mandatory_item }}</th>
                                                        @endif
                                                    @endforeach
                                                    {{-- <td></td> --}}
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    @foreach($resultchecklist as $result)
                                                        @if(in_array($statusPeriod, [1,2]))
                                                            <td class="align-middle text-center"><span class="badge bg-secondary text-white">Not Yet Submit</span></td>
                                                        @else
                                                            <td class="align-middle text-center">{{ $result->graph_percentage }}%</th>
                                                        @endif
                                                    @endforeach
                                                    {{-- <td></td> --}}
                                                </tr>
                                                <tr>
                                                    <td>Result Final</td>
                                                    @foreach($resultchecklist as $result)
                                                        @if(in_array($statusPeriod, [1,2]))
                                                            <td class="align-middle text-center"><span class="badge bg-secondary text-white">Not Yet Submit</span></td>
                                                        @else
                                                            <td class="align-middle text-center">{{ $result->result_final }}</th>
                                                        @endif
                                                    @endforeach
                                                    {{-- <td></td> --}}
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
                                            var typechecklistValues = {!! json_encode($typechecklistValues) !!};
                                            var categories = typechecklistValues.map(function(value) {
                                                return value;
                                            });

                                            var dataGraph = {!! json_encode($dataGraph) !!};

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
                                                    categories: categories,
                                                    tickmarkPlacement: 'on',
                                                    lineWidth: 0
                                                },
                                                yAxis: {
                                                    gridLineInterpolation: 'polygon',
                                                    min: 0, 
                                                    max: 110, 
                                                    tickInterval: 10, 
                                                    lineWidth: 0,
                                                    labels: {
                                                        format: '{value}%', 
                                                        style: {
                                                            fontSize: '8px'
                                                        }
                                                    },
                                                    tickPositions: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 110]
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

                            @endif
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