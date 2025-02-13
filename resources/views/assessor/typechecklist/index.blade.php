@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List Type Checklist ( {{$period->period}} )</h4>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('assessor.listperiod', encrypt($period->id_branch)) }}"
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
                                @if($period->decisionpic == "0")
                                    @if($period->status == null)
                                        <span class="badge bg-warning text-white">Expired</span>
                                    @endif
                                    <span class="badge bg-danger text-white">PIC - Rejected</span>
                                @else
                                    @if($period->status == null)
                                        <span class="badge bg-warning text-white">Expired</span>
                                    @elseif($period->status == 1)
                                        <span class="badge bg-success text-white">Active</span>
                                    @elseif($period->status == 2)
                                        <span class="badge bg-success text-white">Active</span>
                                    @elseif($period->status == 3)
                                        <span class="badge bg-success text-white">Active</span> <span class="badge bg-info text-white">Completed</span>
                                    @elseif($period->status == 4)
                                        <span class="badge bg-success text-white">Assessor Approved</span>
                                    @elseif($period->status == 5)
                                        <span class="badge bg-success text-white">Active</span> <span class="badge bg-danger text-white">Rejected</span>
                                    @elseif($period->status == 6)
                                        <span class="badge bg-success text-white"><i class="mdi mdi-check-underline-circle label-icon"></i> Approved</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @if($period->last_submit_audit != null)
                        <tr>
                            <td class="align-middle"><b>Last Submit Checklist</b></td>
                            <td class="align-middle">: {{ Carbon\Carbon::parse($period->last_submit_audit)->format('d-m-Y H:i:s') }}</td>
                        </tr>
                        @endif
                        @if($historydecision != null)
                        <tr>
                            <td class="align-middle"><b>History Log Decision</b></td>
                            <td class="align-middle">: 
                                <a href="{{ route('assessor.history', encrypt($period->id)) }}" type="button" class="btn btn-sm btn-info">
                                    <i class="mdi mdi mdi-history label-icon"></i> |  History
                                </a>    
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <div class="card">
                    
                    {{-- Approved PIC NOS MD --}}
                    @if($period->status == 4 && in_array(Auth::user()->role, ['Super Admin', 'PIC NOS MD']))
                    <div class="card-header d-flex justify-content-end">
                        <button type="button" class="btn btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#closedapproved"><i class="mdi mdi-message-question label-icon"></i> Your Decision</button>
                        {{-- Modal Approved PIC NOS MD --}}
                        <div class="modal fade" id="closedapproved" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Decision This Period Checklist</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('assessor.closedapproved', encrypt($period->id)) }}" id="formclosed" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <p>
                                                    Your Decission For This Response Checklist?
                                                </p>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="decision" id="approved" value="Approved" checked>
                                                <label class="form-check-label" for="approved">Approved</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="decision" id="notapproved" value="Not Approved">
                                                <label class="form-check-label" for="notapproved">Not Approved</label>
                                            </div>
                                            <div class="mt-2">
                                                <textarea class="form-control" name="note" placeholder="Note (Optional)..." rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sbclosed"><i class="mdi mdi-check-bold label-icon"></i>Submit</button>
                                        </div>
                                    </form>
                                    <script>
                                        document.getElementById('formclosed').addEventListener('submit', function(event) {
                                            if (!this.checkValidity()) {
                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                return false;
                                            }
                                            var submitButton = this.querySelector('button[name="sbclosed"]');
                                            submitButton.disabled = true;
                                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                            return true; // Allow form submission
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($check == 0 && in_array(Auth::user()->role, ['Super Admin', 'Admin', 'Assessor Main Dealer', 'PIC Dealers']))
                    <div class="card-header d-flex justify-content-end">
                        <button type="button" class="btn btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#submit"><i class="mdi mdi-check-bold label-icon"></i> Finish Review</button>
                        {{-- Modal Finish --}}
                        <div class="modal fade" id="submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Finish Review</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('assessor.finishreview', encrypt($period->id)) }}" id="formsubmit" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row mt-2 mb-2">
                                                <h5 class="text-center">
                                                    <b>Are You Sure To Finish This Review?</b>
                                                    <textarea class="form-control mt-4" name="note" placeholder="Note (Optional)..." rows="3"></textarea>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-check-bold label-icon"></i>Finish</button>
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
                    </div>
                    @endif
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive w-100" id="server-side-table" style="font-size: small">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Type Checklist</th>
                                    <th class="align-middle text-center">Total Checklist</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">All Result</th>
                                    <th class="align-middle text-center">Start Date</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(function() {
        $('#server-side-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('assessor.typechecklist', encrypt($period->id)) !!}',
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
                {
                    data: 'type_checklist',
                    name: 'type_checklist',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-bold'
                },
                {
                    data: 'total_checklist',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        html = row.total_checklist-row.checklist_remaining + ' <b>of</b> ' + row.total_checklist;
                        return html;
                    },
                },
                {
                    data: 'status',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.status == null){
                            html = '<span class="badge bg-secondary text-white">Not Started</span>';
                        } else if(row.status == 0){
                            html = '<span class="badge bg-warning text-white">Not Complete</span>';
                        } else if(row.status == 1){
                            html = '<span class="badge bg-info text-white">Complete</span>';
                        } else if(row.status == 2){
                            html = '<span class="badge bg-warning text-white">Review</span>';
                        } else if(row.status == 3){
                            html = '<span class="badge bg-warning text-white">Review</span>';
                        } else if(row.status == 4){
                            html = '<span class="badge bg-danger text-white">Not Approve</span>';
                        } else if(row.status == 5){
                            html = '<span class="badge bg-danger text-white">Not Approve</span>';
                        } else if(row.status == 6){
                            html = '<span class="badge bg-success text-white">Approve</span>';
                        } else if(row.status == 7){
                            html = '<span class="badge bg-success text-white">Approve</span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'resultbutton',
                    name: 'resultbutton',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },              
                {
                    data: 'start_date',
                    name: 'start_date',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.start_date == null){
                            html = '<span class="badge bg-danger text-white">Inactive</span>';
                            return hrml;
                        } else {
                            var startDate = new Date(row.start_date);
                            var endDate = new Date(row.end_date);
                            return startDate.toLocaleDateString('es-CL').replace(/\//g, '-');
                        }
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