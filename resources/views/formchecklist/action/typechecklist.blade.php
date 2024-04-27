
@if($statusperiod == null)
    <span class="badge bg-warning text-white">Expired</span>
@else
    <div class="btn-group" role="group">
        <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            Action <i class="mdi mdi-chevron-down"></i>
        </button>
        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
            @if($data->status == "")
                @if($today >= $startdate)
                    <li><button class="dropdown-item drpdwn" data-bs-toggle="modal" data-bs-target="#start{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Start</button></li>
                @else
                    <li><button class="dropdown-item drpdwn" data-bs-toggle="modal" data-bs-target="#notyetstart{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Start</button></li>
                @endif
            @elseif(in_array($data->status, [0, 1, 5]))
                <li><a class="dropdown-item drpdwn" href="{{ route('formchecklist.checklistform', encrypt($data->id)) }}"><span class="mdi mdi-update"></span> | Check / Update</a></li>
                <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#result{{ $data->id }}"><span class="mdi mdi-dns-outline"></span> | All Result</a></li>
            @elseif(in_array($data->status, [2, 3, 4, 6, 7]))
                <li><a class="dropdown-item drpdwn" href="{{ route('checklistform.detail', encrypt($data->id)) }}"><span class="mdi mdi-information"></span> | Detail</a></li>
                <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#result{{ $data->id }}"><span class="mdi mdi-dns-outline"></span> | All Result</a></li>
            @endif
        </ul>
    </div>

    {{-- MODAL --}}
    <div class="left-align truncate-text">
        {{-- Modal Result --}}
        @if(in_array($data->status, [1, 2, 3, 4, 5, 6, 7]))
            <div class="modal fade" id="result{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-top" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Info Checklist {{ $data->type_checklist }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Total Point :</span></div>
                                        <span>
                                            <span>
                                                @php
                                                    $html = '';
                                                    if ($data->total_point === "" || $data->total_point === null) {
                                                        foreach ($data->point as $point) {
                                                            $html .= '<span class="badge bg-info text-white">'.$point['type_response'].' : '.$point['count'].'</span><br>';
                                                        }
                                                        $html .= '<span class="badge bg-success text-white">Total Point : 0</span><br>';
                                                    } else {
                                                        foreach ($data->point as $point) {
                                                            $html .= '<span class="badge bg-info text-white">'.$point['type_response'].' : '.$point['count'].'</span><br>';
                                                        }
                                                        $html .= '<span class="badge bg-success text-white">Total Point : '.$data->total_point.'</span><br>';
                                                    }
                                                    echo $html;
                                                @endphp
                                            </span>                                    
                                        </span>
                                    </div>
                                </div>
                                <hr class="mt-2">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Persentase Result (%) :</span></div>
                                        <span>
                                            <span>
                                                @if($data->result_percentage == "")
                                                    @php
                                                        $totalPoint = 0;
                                                    @endphp

                                                    @foreach($data->point as $point)
                                                        @if($point['type_response'] == 'Exist, Good')
                                                            @php
                                                                $totalPoint += $point['count'] * 1;
                                                            @endphp
                                                        @elseif($point['type_response'] == 'Exist Not Good')
                                                            @php
                                                                $totalPoint += $point['count'] * -1;
                                                            @endphp
                                                        @elseif($point['type_response'] == 'Not Exist')
                                                            @php
                                                                $totalPoint += $point['count'] * 0;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @if($totalPoint != 0)
                                                        @php
                                                            $result = ($totalPoint / ($data->total_checklist - $data->checklist_remaining)) * 100;
                                                            $formattedResult = number_format((float)$result, 2, '.', '');
                                                        @endphp
                                                    @else
                                                        @php
                                                            $formattedResult = 0;
                                                        @endphp
                                                    @endif
                                                    <!-- Total Point: {{ $totalPoint }} -->
                                                    <!-- Result:  -->
                                                    {{ $formattedResult }} %
                                                @else
                                                    {{$data->result_percentage}} %
                                                @endif
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Audit Result :</span></div>
                                        <span>
                                            <span>
                                            @if($data->result_percentage == "")
                                                @php
                                                    $totalPoint = 0;
                                                @endphp

                                                @foreach($data->point as $point)
                                                    @if($point['type_response'] == 'Exist, Good')
                                                        @php
                                                            $totalPoint += $point['count'] * 1;
                                                        @endphp
                                                    @elseif($point['type_response'] == 'Exist Not Good')
                                                        @php
                                                            $totalPoint += $point['count'] * -1;
                                                        @endphp
                                                    @elseif($point['type_response'] == 'Not Exist')
                                                        @php
                                                            $totalPoint += $point['count'] * 0;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                @if($totalPoint != 0)
                                                    @php
                                                        $result = ($totalPoint / ($data->total_checklist - $data->checklist_remaining)) * 100;
                                                        $formattedResult = number_format((float)$result, 2, '.', '');
                                                    @endphp
                                                @else
                                                    @php
                                                        $formattedResult = 0;
                                                    @endphp
                                                @endif
                                                <!-- Total Point: {{ $totalPoint }} -->
                                                <!-- Result:  -->
                                                {{-- {{ $formattedResult }} % --}}
                                            @else
                                                @php
                                                    $formattedResult = 0;
                                                @endphp
                                            @endif

                                            @if($data->audit_result == "")
                                                @php
                                                    $result_audit = "";
                                                @endphp
                                                @foreach($grading as $item)
                                                    @if($formattedResult >= $item->bottom && $formattedResult <= $item->top)
                                                        @php
                                                            $result_audit = $item->result;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                {{$result_audit}}
                                            @else
                                                {{$data->audit_result}}
                                            @endif
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Mandatory item :</span></div>
                                        <span>
                                            <span>
                                                @if($data->mandatory_item == "")
                                                    @foreach($data->mandatory as $man)
                                                        @if($man['sgp'] != null)
                                                            Bronze
                                                        @else
                                                            @if($man['gp'] != null)
                                                                Silver
                                                            @else
                                                                @if($man['p'] != null)
                                                                    Gold
                                                                @else
                                                                    Platinum
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @else
                                                {{$data->mandatory_item}}
                                                @endif
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Result Final :</span></div>
                                        <span>
                                            <span>
                                                {{$data->result_final}}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Modal Start --}}
        <div class="modal fade" id="start{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Start</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('formchecklist.start', encrypt($data->id)) }}" id="formstart{{ $data->id }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <h1><span class="mdi mdi-play-circle" style="color: #FFA500;"></span></h1>
                                    <h5>Start This Checklist?</h5>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="sb{{ $data->id }}"><i class="mdi mdi-play-circle label-icon"></i>Start</button>
                        </div>
                    </form>
                    <script>
                        $(document).ready(function() {
                            let idList = "{{ $data->id }}";
                            $('#formstart' + idList).submit(function(e) {
                                if (!$('#formstart' + idList).valid()){
                                    e.preventDefault();
                                } else {
                                    $('#sb' + idList).attr("disabled", "disabled");
                                    $('#sb' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        {{-- Modal Not Yet Start --}}
        <div class="modal fade" id="notyetstart{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Start</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h1><span class="mdi mdi-information" style="color: #FFA500;"></span></h1>
                                <h5>Checklist Filling Period Hasn't Started Yet</h5>
                                <p>Will Be Able To Start On Date <b><u>{{ $startdate }}</u></b> Onwards</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif