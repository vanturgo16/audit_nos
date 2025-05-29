@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('auditor.periodList') }}">List Period</a></li>
                            <li class="breadcrumb-item active">Detail {{ $periodInfo->period }}</li>
                        </ol>
                    </div>

                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('auditor.periodList') }}" class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i> Back
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
                            <td class="align-middle">: {{ $periodInfo->period }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b><i>Jaringan</i> Name</b></td>
                            <td class="align-middle">: {{ $periodInfo->dealer_name }} - ({{ $periodInfo->type }})</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Date</b></td>
                            <td class="align-middle">: {{ Carbon\Carbon::parse($periodInfo->start_date)->format('d-m-Y') }} <b> Until </b>{{ Carbon\Carbon::parse($periodInfo->end_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Status</b></td>
                            <td class="align-middle">:
                                @php
                                    $statusLabels = [
                                        0 => '<span class="badge bg-secondary text-white"><i class="mdi mdi-play-box-edit-outline label-icon"></i> Initiate</span>',
                                        1 => '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Assigned - Checklist Process</span>',
                                        2 => '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Revision - Checklist Process</span>',
                                        3 => '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review Assessor</span>',
                                        4 => '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review PIC MD</span>',
                                        5 => '<span class="badge bg-success text-white"><i class="mdi mdi-check-all label-icon"></i> Approved - Done</span>',
                                        'default' => '<span class="badge bg-secondary text-white">Null</span>',
                                    ];
                                @endphp
                                @if($periodInfo->is_active == 1)
                                    {!! $statusLabels[$periodInfo->status] ?? $statusLabels['default'] !!}
                                @else
                                    <span class="badge bg-warning text-white"><i class="mdi mdi-timer-alert-outline label-icon"></i> Expired - Contact Your PIC / Administrator</span>
                                @endif
                            </td>
                        </tr>
                        @if($periodInfo->last_submit_audit)
                        <tr>
                            <td class="align-middle"><b>Last Submit Checklist</b></td>
                            <td class="align-middle">: {{ Carbon\Carbon::parse($periodInfo->last_submit_audit)->format('d-m-Y H:i:s') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="align-middle"><b>Log Activity</b></td>
                            <td class="align-middle">: 
                                <a type="button" href="{{ route('auditor.logActivityPeriod', encrypt($id)) }}" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
                                    <i class="mdi mdi-eye label-icon"></i> View
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <div class="card">
                    <table class="table table-bordered dt-responsive w-100" id="server-side-table" style="font-size: small">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle text-center">No</th>
                                <th rowspan="2" class="align-middle text-center">Type Checklist</th>
                                <th rowspan="2" class="align-middle text-center">Remain</th>
                                <th rowspan="2" class="align-middle text-center">Start Date</th>
                                <th rowspan="2" class="align-middle text-center">Total Point</th>
                                <th rowspan="2" class="align-middle text-center">% Result</th>
                                <th rowspan="2" class="align-middle text-center">Status</th>
                                <th colspan="2" class="align-middle text-center">Approval</th>
                                <th rowspan="2" class="align-middle text-center">Action</th>
                            </tr>
                            <tr>
                                <th class="align-middle text-center">Assessor</th>
                                <th class="align-middle text-center">PIC MD</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="card-footer">
                        @if(in_array(Auth::user()->role, ['Internal Auditor Dealer']))
                            @if(in_array($periodInfo->status, [1, 2]))
                                <button type="button" class="btn btn-success waves-effect btn-label waves-light float-end" 
                                    data-bs-toggle="modal" data-bs-target="#submit" @if($allComplete != 1) disabled @endif>
                                    <i class="mdi mdi-check-bold label-icon"></i>Submit Checklist
                                </button>
                                {{-- Modal Submit --}}
                                <div class="modal fade" id="submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Submit</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('auditor.submit', encrypt($id)) }}" id="formsubmit" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12 text-center">
                                                            <h1><span class="mdi mdi-bell-alert" style="color: #FFA500;"></span></h1>
                                                            <h5>Are You Sure to Submit Your Answer For This Checklist?</h5>
                                                            <p>(You are no longer to edit next!)</p>
                                                        </div>
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#server-side-table').DataTable({
            bLengthChange: false, // Hide the "Show entries" dropdown
            bFilter: false,       // Hide the search box
            paging: false,        // Hide the pagination
            info: false,          // Hide the "Showing X of Y entries" info
            processing: true,
            serverSide: true,
            ajax: '{!! route('auditor.periodDetail', encrypt($id)) !!}',
            columns: [
                {
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    visible: false,
                    className: 'align-middle text-center',
                },
                {
                    data: 'type_checklist',
                    name: 'type_checklist',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var check = row.total_checklist - row.checklist_remaining;
                        return  '<h6><u><b>' + row.type_checklist + '</b></u></h6>' + check + '<b> of </b>' + row.total_checklist;
                    },
                },
                {
                    data: 'checklist_remaining',
                    name: 'checklist_remaining',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'start_date',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html;
                        if (row.start_date === null) {
                            html = '-';
                        } else {
                            var startDate = new Date(row.start_date);
                            html = startDate.toLocaleDateString('es-CL').replace(/\//g, '-');
                        }
                        return html;
                    },
                },
                {
                    data: 'total_point',
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.total_point === "" || row.total_point === null) {
                            html = '-';
                        } else {
                            row.point.forEach(function(point) {
                                html += '<span class="badge bg-info text-white">' + point.type_response + ' : ' + point.count + '</span><br>';
                            });
                            html += '<span class="badge bg-success text-white mt-2"><u>Total Point : ' + row.total_point + '</u></span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'result_percentage',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.result_percentage === "" || row.result_percentage === null) {
                            html = '-';
                        } else {
                            html = row.result_percentage + ' %';
                        }
                        return html;
                    },
                },
                {
                    data: 'status',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.status === "" || row.status === null) {
                            html = '<span class="badge bg-secondary text-white">Not Started</span>';
                        } else if (row.status == 0) {
                            html = '<span class="badge bg-warning text-white"><i class="mdi mdi-sync label-icon"></i> Checklist Process</span>';
                        } else if (row.status == 1) {
                            html = '<span class="badge bg-success text-white"><i class="mdi mdi-check-circle-outline label-icon"></i> Assessor Approve</span>';
                        } else if (row.status == 2) {
                            html = '<span class="badge bg-warning text-white"><i class="mdi mdi-message-draw label-icon label-icon"></i> Review Assessor</span>';
                        } else if (row.status == 3) {
                            html = '<span class="badge bg-warning text-white"><i class="mdi mdi-message-draw label-icon label-icon"></i> Review PIC MD</span>';
                        } else if (row.status == 4) {
                            html = '<span class="badge bg-warning text-white"><i class="mdi mdi-sync label-icon"></i> Revision - Checklist Process</span>';
                        } else if (row.status == 5) {
                            html = '<span class="badge bg-success text-white"><i class="mdi mdi-check-all label-icon"></i> Approved - Done</span>';
                        } else {
                            html = '-';
                        }

                        if(row.status == 0 || row.status == 4){
                            if(row.checklist_remaining == 0){
                                if(row.type_checklist == 'H1 Premises'){
                                    html += '<br><span class="badge bg-success text-white">Complete</span>';
                                } else {
                                    if(row.isComplete == 1){
                                        html += '<br><span class="badge bg-success text-white">Complete</span>';
                                    }
                                }
                            }
                        }
                        
                        return html;
                    },
                },
                {
                    data: 'last_decision_assessor',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.last_decision_assessor == 0) {
                            html = '<span class="badge bg-warning text-white"><i class="mdi mdi-refresh label-icon"></i></span>';
                        } else if (row.last_decision_assessor == 1) {
                            html = '<span class="badge bg-danger text-white"><i class="mdi mdi-close-circle-outline label-icon"></i></span>';
                        } else if (row.last_decision_assessor == 2) {
                            html = '<span class="badge bg-success text-white"><i class="mdi mdi-check-circle-outline label-icon"></i></span>';
                        } else {
                            html = '-';
                        }
                        return html;
                    },
                },
                {
                    data: 'last_decision_pic',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.last_decision_pic == 0) {
                            html = '<span class="badge bg-warning text-white"><i class="mdi mdi-refresh label-icon"></i></span>';
                        } else if (row.last_decision_pic == 1) {
                            html = '<span class="badge bg-danger text-white"><i class="mdi mdi-close-circle-outline label-icon"></i></span>';
                        } else if (row.last_decision_pic == 2) {
                            html = '<span class="badge bg-success text-white"><i class="mdi mdi-check-circle-outline label-icon"></i></span>';
                        } else {
                            html = '-';
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
            ],
        });
    });
</script>

@endsection