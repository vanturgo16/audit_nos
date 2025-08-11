@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between py-0 mb-3">
                    <h4 class="mb-sm-0 font-size-25">
                        Assign Checklist
                    </h4>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('periodchecklist.index') }}" class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
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
                        <h6 class="mb-0">{{ $period->period ?? '-' }}</h6><i class="mdi mdi-chevron-up arrow-icon"></i>
                    </a>
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-0">
                            <table class="table table-bordered dt-responsive nowrap w-100">
                                <tbody>
                                    <tr>
                                        <td class="align-top"><b><i>Jaringan</i> Name</b></td>
                                        <td class="align-top no-right-border" style="width: 1%">:</td>
                                        <td class="align-top no-left-border">{{ $period->dealer_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="align-top"><b>Date</b></td>
                                        <td class="align-top no-right-border" style="width: 1%">:</td>
                                        <td class="align-top no-left-border">
                                            {{ \Carbon\Carbon::parse($period->start_date)->format('d-m-Y') }}
                                            <b> Until </b>
                                            {{ \Carbon\Carbon::parse($period->end_date)->format('d-m-Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-top"><b>Status</b></td>
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

                                            @if($period->is_active == 1)
                                                {!! $statusLabels[$period->status] ?? $statusLabels['default'] !!}
                                            @else
                                                <span class="badge bg-warning text-white"><i class="mdi mdi-timer-alert-outline label-icon"></i> Expired</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <table class="table table-bordered table-hover table-striped dt-responsive w-100" id="ssTable">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle text-center">No</th>
                                <th class="align-middle text-center">Type Checklist</th>
                                <th class="align-middle text-center">Total Parent Checklist</th>
                                <th class="align-middle text-center">Total Checklist</th>
                                <th class="align-middle text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="card-footer">
                        @php
                            $role = Auth::user()->role;
                            $status = $period->status;
                            $isActive = $period->is_active;
                            $showSubmitAssign = in_array($role, ['Super Admin', 'Admin', 'PIC Dealer']) && $isActive == 1 && $status == 0;
                        @endphp

                        @if($showSubmitAssign)
                            <button type="button" class="btn btn-success waves-effect btn-label waves-light float-end" 
                                data-bs-toggle="modal" data-bs-target="#submit" @if($check != 1) disabled @endif>
                                <i class="mdi mdi-check-bold label-icon"></i> Assign To Internal Auditor
                            </button>
                            {{-- Modal Submit --}}
                            <div class="modal fade" id="submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Submit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form class="formLoad" action="{{ route('assignchecklist.submit', encrypt($period->id)) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 text-center">
                                                        <h1><span class="mdi mdi-bell-alert" style="color: #FFA500;"></span></h1>
                                                        <h5>Start Submit This Assign Checklist?</h5>
                                                        <p>(You are no longer to edit next!)</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                                                    <i class="mdi mdi-check-bold label-icon"></i>Submit
                                                </button>
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
        $('#ssTable').DataTable({
            bLengthChange: false, // Hide the "Show entries" dropdown
            bFilter: false,       // Hide the search box
            paging: false,        // Hide the pagination
            info: false,          // Hide the "Showing X of Y entries" info
            processing: true,
            serverSide: true,
            ajax: '{!! route('assignchecklist.index', encrypt($period->id)) !!}',
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
                    orderable: true,
                    searchable: true,
                    data: 'name_value',
                    name: 'name_value',
                    className: 'align-middle text-bold',
                },
                {
                    orderable: true,
                    searchable: true,
                    data: 'countParent',
                    className: 'align-middle text-center text-bold',
                    render: function(data, type, row) {
                        var html
                        if(row.countParent == 0){
                            html = '<span class="badge bg-secondary text-white">Not Set</span>';
                        } else {
                            html = '<h5><span class="badge bg-success text-white">' + row.countParent + '</span></h5>';
                        }
                        return html;
                    },
                },
                {
                    orderable: true,
                    searchable: true,
                    data: 'countCheck',
                    className: 'align-middle text-center text-bold',
                    render: function(data, type, row) {
                        var html
                        if(row.countCheck == 0){
                            html = '<span class="badge bg-secondary text-white">Not Set</span>';
                        } else {
                            html = '<h5><span class="badge bg-success text-white">' + row.countCheck + '</span></h5>';
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
            ],
        });
    });
</script>
@endsection