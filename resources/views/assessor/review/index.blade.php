@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Review Checklist</h4>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('assessor.typechecklist', encrypt($type->id_periode)) }}"
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
                            <td class="align-middle"><b>Type Checklist</b></td>
                            <td class="align-middle">: {{ $type->type_checklist }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Status</b></td>
                            <td class="align-middle">: 
                                @if($type->status == null)
                                    <span class="badge bg-secondary text-white">Not Started</span>
                                @elseif($type->status == 0)
                                    <span class="badge bg-warning text-white">Not Complete</span>
                                @elseif($type->status == 1)
                                    <span class="badge bg-info text-white">Complete</span>
                                @elseif($type->status == 2)
                                    <span class="badge bg-warning text-white">Review</span>
                                @elseif($type->status == 3)
                                    <span class="badge bg-danger text-white">Not Approve</span>
                                @elseif($type->status == 4)
                                    <span class="badge bg-success text-white">Approve</span>
                                @endif  
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <div class="card">
                    @if($type->status == 2 && in_array(Auth::user()->role, ['Super Admin', 'Admin', 'Assessor Main Dealer']))
                    <div class="card-header d-flex justify-content-end">
                        <button type="button" class="btn btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#submit"><i class="mdi mdi-check-bold label-icon"></i> Decission</button>
                        {{-- Modal Finish --}}
                        <div class="modal fade" id="submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Decission Review</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('assessor.submitreview', encrypt($type->id)) }}" id="formsubmit" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="idperiod" value="{{ $type->id_periode }}">
                                            <input type="hidden" name="typechecklist" value="{{ $type->type_checklist }}">
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
                                            <div class="mt-2" id="reasonBox" style="display:none;">
                                                <textarea class="form-control" name="reason" id="reason" placeholder="Reason Why Not Approved..." rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-check-bold label-icon"></i>Send</button>
                                        </div>
                                    </form>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            var approvedRadio = document.getElementById('approved');
                                            var notApprovedRadio = document.getElementById('notapproved');
                                            var reasonBox = document.getElementById('reasonBox');
                                            var reasonInput = document.getElementById('reason');

                                            approvedRadio.addEventListener('change', function () {
                                                reasonBox.style.display = 'none';
                                                reasonInput.removeAttribute('required');
                                            });

                                            notApprovedRadio.addEventListener('change', function () {
                                                reasonBox.style.display = 'block';
                                                reasonInput.setAttribute('required', 'required');
                                            });
                                        });

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
                        <table class="table table-bordered dt-responsive nowrap w-100" id="server-side-table">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Parent Point</th>
                                    <th class="align-middle text-center">Child Point</th>
                                    <th class="align-middle text-center">Sub Point</th>
                                    <th class="align-middle text-center">Response</th>
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
            ajax: '{!! route('assessor.review', encrypt($type->id)) !!}',
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
                    data: 'parent_point',
                    name: 'parent_point',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-bold'
                },
                {
                    data: 'child_point_checklist',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.child_point_checklist == null){
                            html = '<span class="badge bg-secondary text-white">Null</span>';
                        } else {
                            html = row.child_point_checklist;
                        }
                        return html;
                    },
                },
                {
                    data: 'sub_point_checklist',
                    name: 'sub_point_checklist',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'response',
                    name: 'response',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
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