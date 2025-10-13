@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('listassigned.periodList') }}">List Assigned Checklist</a></li>
                            <li class="breadcrumb-item active">{{ $periodInfo->period }}</li>
                        </ol>
                    </div>

                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('listassigned.periodList') }}" class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <a href="javascript:void(0);" class="d-flex justify-content-between align-items-center card-header py-3 toggle-collapse" 
                        data-target="#collapseCardExample" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to show or hide detail">
                        <h6 class="mb-0">{{ $periodInfo->period ?? '-' }}</h6><i class="mdi mdi-chevron-up arrow-icon"></i>
                    </a>
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-0">
                            <table class="table table-bordered dt-responsive nowrap w-100">
                                <tbody>
                                    <tr>
                                        <td class="align-top fw-bold"><i>Jaringan</i> Name</td>
                                        <td class="align-top no-right-border" style="width: 1%">:</td>
                                        <td class="align-top no-left-border">{{ $periodInfo->dealer_name }} - ({{ $periodInfo->type }})</td>
                                    </tr>
                                    <tr>
                                        <td class="align-top fw-bold">Date</td>
                                        <td class="align-top no-right-border" style="width: 1%">:</td>
                                        <td class="align-top no-left-border">
                                            {{ \Carbon\Carbon::parse($periodInfo->start_date)->format('d-m-Y') }}
                                            <b> Until </b>
                                            {{ \Carbon\Carbon::parse($periodInfo->end_date)->format('d-m-Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-top fw-bold">Status</td>
                                        <td class="align-top no-right-border" style="width: 1%">:</td>
                                        <td class="align-top no-left-border">
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
                                                <span class="badge bg-warning text-white"><i class="mdi mdi-timer-alert-outline label-icon"></i> Expired</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($periodInfo->last_submit_audit)
                                    <tr>
                                        <td class="align-top fw-bold">Last Submit Checklist</td>
                                        <td class="align-top no-right-border" style="width: 1%">:</td>
                                        <td class="align-top no-left-border">{{ Carbon\Carbon::parse($periodInfo->last_submit_audit)->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="align-top fw-bold">Auditor</td>
                                        <td class="align-top no-right-border" style="width: 1%">:</td>
                                        <td class="align-top no-left-border">{{ $periodInfo->auditor_name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="align-top fw-bold">Log Activity</td>
                                        <td class="align-top no-right-border" style="width: 1%">:</td>
                                        <td class="align-top no-left-border">
                                            <a type="button" href="{{ route('listassigned.logActivity', encrypt($id)) }}" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
                                                <i class="mdi mdi-eye label-icon"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card p-0">
                    <div class="card-header p-3">
                        <div class="row">
                            <div class="col-6">
                                <h5>List Type Checklist</h5>
                            </div>
                            <div class="col-6">
                                {{-- Export --}}
                                @if(in_array(Auth::user()->role, ['Super Admin', 'Admin', 'PIC NOS MD']) && in_array($periodInfo->status, [5]))
                                    <a href="{{ route('export.period', encrypt($id)) }}" type="button" class="btn btn-secondary waves-effect btn-label waves-light float-end" id="exportBtn">
                                        <i class="mdi mdi-file-excel label-icon"></i>Export To Excel
                                    </a>
                                    <script>
                                        $(document).ready(function() {
                                            $("#exportBtn").click(function() {
                                                // Load Button
                                                var button = this; button.disabled = true;
                                                button.classList.remove("waves-effect", "btn-label", "waves-light");
                                                button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Please wait...';
                                                setTimeout(function () {
                                                    button.innerHTML = '<i class="mdi mdi-file-excel label-icon"></i>Export To Excel';
                                                    button.classList.add("waves-effect", "btn-label", "waves-light");
                                                    button.disabled = false;
                                                }, 3000);
                                            });
                                        });
                                    </script>
                                @endif
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-hover table-striped dt-responsive w-100" id="ssTable" style="font-size: small">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="align-middle text-center">No</th>
                                <th rowspan="2" class="align-middle text-center">Type Checklist</th>
                                <th colspan="2" class="align-middle text-center">Total Point</th>
                                <th colspan="2" class="align-middle text-center">% Result</th>
                                <th colspan="2" class="align-middle text-center">Result Final</th>
                                <th rowspan="2" class="align-middle text-center">Status</th>
                                <th colspan="2" class="align-middle text-center">Approval</th>
                                <th rowspan="2" class="align-middle text-center">Action</th>
                            </tr>
                            <tr>
                                <th class="align-middle text-center">Auditor</th>
                                <th class="align-middle text-center">Assesor</th>
                                <th class="align-middle text-center">Auditor</th>
                                <th class="align-middle text-center">Assesor</th>
                                <th class="align-middle text-center">Auditor</th>
                                <th class="align-middle text-center">Assesor</th>
                                <th class="align-middle text-center">Assessor</th>
                                <th class="align-middle text-center">PIC MD</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="card-footer">
                        @php
                            $role = Auth::user()->role;
                            $userId = Auth::id();
                            $status = $periodInfo->status;
                            $showSubmitAuditor = $role == 'Internal Auditor Dealer' && $userId == $periodInfo->idAuditor && in_array($status, [1, 2]);
                            $showSubmitAssesor = $role == 'Assessor Main Dealer' && in_array($userId, $idAssesors) && $status == 3;
                            $showSubmitPIC = $role == 'PIC NOS MD' && $status == 4;
                        @endphp

                        {{-- Submit Auditor --}}
                        @if($showSubmitAuditor)
                            <button type="button" class="btn btn-success waves-effect btn-label waves-light float-end" 
                                data-bs-toggle="modal" data-bs-target="#submitAuditor" @if($allCompleteCheck != 1) disabled @endif>
                                <i class="mdi mdi-check-bold label-icon"></i>Submit Checklist
                            </button>
                            {{-- Modal Submit --}}
                            <div class="modal fade" id="submitAuditor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Submit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form class="formLoad" action="{{ route('auditor.submit', encrypt($id)) }}" method="POST">
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
                                                <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-check-bold label-icon"></i>Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Submit Assesor --}}
                        @if($showSubmitAssesor)
                            @if($isCorrection)
                                <button type="button" class="btn btn-success waves-effect btn-label waves-light float-end"
                                    data-bs-toggle="modal" data-bs-target="#submitCorrection">
                                    <i class="mdi mdi-check-bold label-icon"></i>Submit Correction
                                </button>
                                {{-- Modal Correction --}}
                                <div class="modal fade" id="submitCorrection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Submit Correction</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="formLoad" action="{{ route('review.submitCorrectionChecklist', encrypt($id)) }}" method="POST">
                                                @csrf
                                                <div class="modal-body" style="max-height: 67vh; overflow-y: auto;">
                                                    <div class="row p-4">
                                                        <div class="col-12 text-center">
                                                            <h5>Are You Sure to Submit Your Correction For This Checklist?</h5>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 mt-4">
                                                            <div class="form-group">
                                                                <h5 class="fw-bold">Note (Optional)</h5>
                                                                <textarea id="ckeditor-classic" name="note"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-check-bold label-icon"></i>Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button type="button" class="btn btn-success waves-effect btn-label waves-light float-end"
                                    data-bs-toggle="modal" data-bs-target="#submit" @if($allReviewedAssesor != 1) disabled @endif>
                                    <i class="mdi mdi-check-bold label-icon"></i>Submit Review
                                </button>
                                {{-- Modal Submit --}}
                                <div class="modal fade" id="submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Submit</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="formLoad" action="{{ route('review.submitReviewChecklist', encrypt($id)) }}" method="POST">
                                                @csrf
                                                <div class="modal-body" style="max-height: 67vh; overflow-y: auto;">
                                                    <div class="row p-4">
                                                        <div class="col-12 text-center">
                                                            <h5>Are You Sure to Submit Your Review For This Checklist?</h5>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 mt-4">
                                                            <div class="form-group">
                                                                <h5 class="fw-bold">Note (Optional)</h5>
                                                                <textarea id="ckeditor-classic" name="note"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-check-bold label-icon"></i>Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Submit PIC --}}
                        @if($showSubmitPIC)
                            <button type="button" class="btn btn-success waves-effect btn-label waves-light float-end"
                                data-bs-toggle="modal" data-bs-target="#submit" @if($allReviewedPIC != 1) disabled @endif>
                                <i class="mdi mdi-check-bold label-icon"></i>Submit Review
                            </button>
                            {{-- Modal Submit --}}
                            <div class="modal fade" id="submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Submit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form class="formLoad" action="{{ route('review.submitPICReviewChecklist', encrypt($id)) }}" method="POST">
                                            @csrf
                                            <div class="modal-body" style="max-height: 67vh; overflow-y: auto;">
                                                <div class="row p-4">
                                                    <div class="col-12 text-center">
                                                        <h5>Are You Sure to Submit Your Review For This Checklist?</h5>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 mt-4">
                                                        <div class="form-group">
                                                            <h5 class="fw-bold">Note (Optional)</h5>
                                                            <textarea id="ckeditor-classic" name="note"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-check-bold label-icon"></i>Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var role = "{{ Auth::user()->role }}";
        $('#ssTable').DataTable({
            bLengthChange: false, // Hide the "Show entries" dropdown
            bFilter: false,       // Hide the search box
            paging: false,        // Hide the pagination
            info: false,          // Hide the "Showing X of Y entries" info
            processing: true,
            serverSide: true,
            ajax: '{!! route('listassigned.periodDetail', encrypt($id)) !!}',
            columns: [
                {
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    // visible: false,
                    className: 'align-top text-center fw-bold',
                },
                {
                    data: 'type_checklist',
                    name: 'type_checklist',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var check = row.total_checklist - row.checklist_remaining;
                        var start;
                        if (row.start_date === null) {
                            start = '-';
                        } else {
                            var startDate = new Date(row.start_date);
                            start = startDate.toLocaleDateString('es-CL').replace(/\//g, '-');
                        }

                        return `
                            <div style="display: flex; flex-direction: column; justify-content: space-between; min-height: 100px;">
                                <div>
                                    <h5><u><b>${row.type_checklist}</b></u></h5>
                                    ${check} <b>of</b> ${row.total_checklist}
                                </div>
                                <div class="mt-2">
                                    <small><b>Start Audit:</b> ${start}</small>
                                </div>
                            </div>`;
                        return  `
                            <h5><u><b>${row.type_checklist}</b></u></h5>
                            ${check} <b>of</b> ${row.total_checklist}<br>
                            <br><small><b>Start Audit:</b> ${start}</small>`;
                    },
                },
                {
                    data: 'total_point',
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        if (data === "" || data === null) {
                            return '-';
                        }
                        let tableRows = '';
                        row.point.forEach(function(point) {
                            tableRows += `
                                <tr>
                                    <th class="align-top px-2 py-0" style="font-weight: bold; border: none;">
                                        <small>${point.type_response}</small>
                                    </th>
                                    <th class="align-top px-0 py-0" style="font-weight: bold; border: none;">
                                        <small>:</small>
                                    </th>
                                    <td class="align-top px-2 py-0" style="border: none;">
                                        <small><b>${point.count}</b></small>
                                    </td>
                                </tr>`;
                        });

                        return `
                            <div style="display: flex; flex-direction: column; justify-content: space-between; min-height: 100px;">
                                <div>
                                    <table class="mb-0" style="border-collapse: collapse; width: 100%;">
                                        <tbody>
                                            ${tableRows}
                                        </tbody>
                                    </table>
                                </div>
                                <div class="align-top px-2 py-0">
                                    <strong>Total Point : <b><u>${data}</u></b></strong>
                                </div>
                            </div>`;
                    },
                },
                {
                    data: 'total_point_assesor',
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        if (data === "" || data === null) {
                            return '-';
                        }
                        let tableRows = '';
                        row.point_correction.forEach(function(point) {
                            tableRows += `
                                <tr>
                                    <th class="align-top px-2 py-0" style="font-weight: bold; border: none;">
                                        <small>${point.type_response}</small>
                                    </th>
                                    <th class="align-top px-0 py-0" style="font-weight: bold; border: none;">
                                        <small>:</small>
                                    </th>
                                    <td class="align-top px-2 py-0" style="border: none;">
                                        <small><b>${point.count}</b></small>
                                    </td>
                                </tr>`;
                        });

                        return `
                            <div style="display: flex; flex-direction: column; justify-content: space-between; min-height: 100px;">
                                <div>
                                    <table class="mb-0" style="border-collapse: collapse; width: 100%;">
                                        <tbody>
                                            ${tableRows}
                                        </tbody>
                                    </table>
                                </div>
                                <div class="align-top px-2 py-0">
                                    <strong>Total Point : <b><u>${data}</u></b></strong>
                                </div>
                            </div>`;
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
                    data: 'result_percentage_assesor',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.result_percentage_assesor === "" || row.result_percentage_assesor === null) {
                            html = '-';
                        } else {
                            html = row.result_percentage_assesor + ' %';
                        }
                        return html;
                    },
                },
                {
                    data: 'result_final',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.result_final === "" || row.result_final === null) {
                            html = '-';
                        } else {
                            html = row.result_final;
                        }
                        return html;
                    },
                },
                {
                    data: 'result_final_assesor',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.result_final_assesor === "" || row.result_final_assesor === null) {
                            html = '-';
                        } else {
                            html = row.result_final_assesor;
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
                        if(row.status == 2 && role == 'Assessor Main Dealer'){
                            if(row.reviewed == 1){
                                html += '<br><span class="badge bg-success text-white">Reviewed All</span>';
                            }
                        }
                        if(row.status == 3 && role == 'PIC NOS MD'){
                            if(row.last_decision_pic != 0){
                                html += '<br><span class="badge bg-success text-white">Reviewed</span>';
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
                        var assesorName = row.assesor_name ?? '-';
                        if (row.last_decision_assessor == 0) {
                            html = '<span class="badge bg-warning text-white"><i class="mdi mdi-refresh label-icon"></i></span>';
                        } else if (row.last_decision_assessor == 1) {
                            html = '<span class="badge bg-danger text-white"><i class="mdi mdi-close-circle-outline label-icon"></i></span>';
                        } else if (row.last_decision_assessor == 2) {
                            if(row.last_correction_assessor === null){
                                html = '<span class="badge bg-success text-white"><i class="mdi mdi-check-circle-outline label-icon"></i></span>';
                            } else if(row.last_correction_assessor === 0){
                                html = '<span class="badge bg-warning text-white">correcting..</span>';
                            } else if(row.last_correction_assessor == 1){
                                html = '<span class="badge bg-success text-white"><i class="mdi mdi-check-circle-outline label-icon"></i></span>';
                            }
                        } else {
                            html = '-';
                        }
                        
                        return html + '<br><br>' + assesorName;
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