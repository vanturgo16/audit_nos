@extends('layouts.master')
@section('konten')


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Tabel Type Checklist ( {{ $period->period }} )</h4>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('formchecklist.periode', encrypt($id_jaringan)) }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>Period Name</b></td>
                            <td class="align-middle">: {{ $period->period }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Date</b></td>
                            <td class="align-middle">: {{ Carbon\Carbon::parse($period->start_date)->format('d-m-Y') }} <b> Until </b>{{ Carbon\Carbon::parse($period->end_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Status</b></td>
                            <td class="align-middle">:
                                @if($period->status == 1)
                                    <span class="badge bg-success text-white">Active</span>
                                @elseif($period->status == 2)
                                    <span class="badge bg-success text-white">Active</span>
                                @elseif($period->status == 3)
                                    <span class="badge bg-success text-white">Active</span> <span class="badge bg-info text-white">Completed</span>
                                @elseif($period->status == 4)
                                    <span class="badge bg-success text-white">Closed Approved</span>
                                @elseif($period->status == 5)
                                    <span class="badge bg-success text-white">Active</span> <span class="badge bg-danger text-white">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if($status == true)
                        <button type="button" class="btn btn-success waves-effect btn-label waves-light float-end" data-bs-toggle="modal" data-bs-target="#submit"><i class="mdi mdi-check-bold label-icon"></i>Submit</button>
                        @endif
                    </div>
                    <div class="card-body">

                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Type Checklist</th>
                                    <th class="align-middle text-center">Total Checklist</th>
                                    <th class="align-middle text-center">Checklist Remain</th>
                                    <th class="align-middle text-center">Total Point</th>
                                    <th class="align-middle text-center">% Result</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Result Audit</th>
                                    <th class="align-middle text-center">Mandatory item</th>
                                    <th class="align-middle text-center">Start Date</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0;?>
                                @foreach ($datas as $data)
                                <?php $no++ ;?>
                                    <tr>
                                        <td class="align-middle text-center">{{ $no }}</td>
                                        <td class="align-middle text-center">{{ $data->type_checklist }}</td>
                                        <td class="align-middle text-center">{{ $data->total_checklist - $data->checklist_remaining}} of {{ $data->total_checklist}}</td>
                                        <td class="align-middle text-center">{{ $data->checklist_remaining}}</td>
                                        <td class="align-middle text-center">
                                            @if($data->total_point == "")
                                                @foreach($data->point as $point)
                                                    <span class="badge bg-info text-white">{{$point['type_response']}} : {{$point['count']}}</span>
                                                    <br>
                                                @endforeach
                                            @else
                                                {{$data->total_point}}
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
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

                                        </td>
                                        <td class="align-middle text-center">
                                            @if($data->status == "")
                                                <span class="badge bg-secondary text-white">Not Started</span>
                                            @elseif($data->status == 0)
                                                <span class="badge bg-warning text-white">Not Complete</span>
                                            @elseif($data->status == 1)
                                                <span class="badge bg-info text-white">Complete</span>
                                            @elseif($data->status == 2)
                                                <span class="badge bg-warning text-white">Reviewed</span>
                                            @elseif($data->status == 3)
                                                <span class="badge bg-warning text-white">Reviewed</span>
                                            @elseif($data->status == 4)
                                                <span class="badge bg-warning text-white">Reviewed</span>
                                            @elseif($data->status == 5)
                                                <button type="button" class="btn btn-sm btn-danger waves-effect btn-label waves-light float-end" data-bs-toggle="modal" data-bs-target="#notapprove{{$data->id}}"><i class="mdi mdi-sim-alert label-icon"></i>Not Approve</button>
                                                <div class="modal fade" id="notapprove{{$data->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-top" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="staticBackdropLabel">Info Not Approve</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <div><span class="fw-bold">Reson :</span></div>
                                                                            <span>
                                                                                <span>{{ $data->last_reason }}</span>
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
                                            @elseif($data->status == 6)
                                                <span class="badge bg-warning text-white">Reviewed</span>
                                            @elseif($data->status == 7)
                                                <span class="badge bg-success text-white">Approve</span>
                                            @endif

                                        </td>
                                        <td class="align-middle text-center">
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
                                        </td>
                                        <td class="align-middle text-center">
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
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($data->start_date == null)
                                                <span class="badge bg-secondary text-white">Not Started</span>
                                            @else
                                                {{ Carbon\Carbon::parse($data->start_date)->format('d-m-Y') }}
                                            @endif
                                        </td>


                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">

                                                    @if($data->status == "")
                                                        <li><button class="dropdown-item drpdwn" data-bs-toggle="modal" data-bs-target="#start{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Start</button></li>
                                                    @elseif($data->status == 0 && $data->checklist_remaining != 0)
                                                        <li><a class="dropdown-item drpdwn" href="{{ route('formchecklist.checklistform', encrypt($data->id)) }}"><span class="mdi mdi-check-underline-circle"></span> | Update</a></li>
                                                    @elseif($data->status == 0)
                                                        <li><a class="dropdown-item drpdwn" href="{{ route('formchecklist.checklistform', encrypt($data->id)) }}"><span class="mdi mdi-check-underline-circle"></span> | Update</a></li>
                                                    @elseif($data->status == 1)
                                                        <li><a class="dropdown-item drpdwn" href="{{ route('formchecklist.checklistform', encrypt($data->id)) }}"><span class="mdi mdi-check-underline-circle"></span> | Update</a></li>
                                                    @elseif($data->status == 2)
                                                        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Detail</a></li>
                                                    @elseif($data->status == 3)
                                                        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Detail</a></li>
                                                    @elseif($data->status == 4)
                                                        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Detail</a></li>
                                                    @elseif($data->status == 5)
                                                        <li><a class="dropdown-item drpdwn" href="{{ route('formchecklist.checklistform', encrypt($data->id)) }}"><span class="mdi mdi-check-underline-circle"></span> | Update</a></li>
                                                    @elseif($data->status == 6)
                                                        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Detail</a></li>
                                                    @elseif($data->status == 7)
                                                        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Detail</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
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
                                                                $('#sb' + idList).html('<i class="mdi mdi-loading mdi-spin"></i> Please wait...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    @if($status == true)
                    {{-- Modal Submit --}}
                    <div class="modal fade" id="submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-top" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Submit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('formchecklist.submitchecklist', encrypt($id)) }}" id="formsubmit" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <p>
                                                You Want to Submit answer this checklist {{$period->period}}?
                                                (You are not longer to edit this checklist!)
                                            </p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-check-bold label-icon"></i>Submit</button>
                                    </div>
                                </form>
                                <script>
                                    document.getElementById('formsubmit').addEventListener('submit', function(event) {
                                        if (!this.checkValidity()) {
                                            event.preventDefault(); // Prevent form submission if it's not valid
                                            return false;
                                        }
                                        var submitButton = this.querySelector('button[name="sb"]');
                                        submitButton.disabled = true;
                                        submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                        return true; // Allow form submission
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>





@endsection