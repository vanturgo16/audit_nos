@extends('layouts.master')

@section('konten')

<style>    
    /* Style Image Hover */
    .custom-image-container {
        position: relative;
        width: 100%;
        height: 7vh;
        overflow: hidden;
    }
    .custom-image-container:hover .custom-overlay {
        opacity: 1;
    }
    .custom-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .custom-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .custom-text {
        color: white;
        font-size: 10px;
        text-align: center;
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between py-0 mb-3">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('review.periodList') }}">List Period</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('review.periodDetail', encrypt($period->id)) }}">{{ $period->period }}</a></li>
                            <li class="breadcrumb-item active">Review {{ $typeCheck }}</li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('review.periodDetail', encrypt($period->id)) }}"
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
            @php
                function getBadge($value) {
                    if (!$value) return '-';
                    $badgeStyle = match ($value) {
                        'Bronze' => 'background-color: #cd7f32; color: white;',
                        'Silver' => 'background-color: #c0c0c0; color: black;',
                        'Gold' => 'background-color: #ffd700; color: black;',
                        'Platinum' => 'background: linear-gradient(135deg, #e5e4e2 0%, #f2f2f2 100%); color: black; text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.6); border: 1px solid #dcdcdc; box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.3);', // Shiny Platinum effect
                        default => 'background-color: #f8f9fa; color: black;',
                    };
                    return "<span class='badge' style='$badgeStyle'>$value</span>";
                }
            @endphp
            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>% Result</b></td>
                            <td class="align-middle">: {{ $chekJar->result_percentage }}%</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Result Audit</b></td>
                            <td class="align-middle">: {!! getBadge($chekJar->audit_result) !!}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Mandatory Item</b></td>
                            <td class="align-middle">: {!! getBadge($chekJar->mandatory_item) !!}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>RESULT FINAL</b></td>
                            <td class="align-middle">: {!! getBadge($chekJar->result_final) !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-top">
                                <b>Last Note Assessor</b>
                                @if(Auth::user()->role == 'Assessor Main Dealer')
                                    @if($chekJar->status === 2)
                                        <br>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editnote">
                                            <span class="mdi mdi-pen"></span> Edit Note
                                        </button>
                                        <div class="modal fade" id="editnote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Note</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('review.updateNoteChecklist', encrypt($id)) }}" method="post" enctype="multipart/form-data" id="updnotes">
                                                        @csrf
                                                        <div class="modal-body" style="max-height: 65vh; overflow-x:auto;">
                                                            <div class="row px-2">
                                                                <div class="col-12">
                                                                    <textarea id="ckeditor-classic" name="note">{!! $chekJar->last_reason_assessor !!}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" id="btnUpdNotes"><i class="fas fa-sync fa-fw" aria-hidden="true"></i> Update</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        document.getElementById('updnotes').addEventListener('submit', function(event) {
                                                            if (!this.checkValidity()) {
                                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                                return false;
                                                            }
                                                            var submitButton = this.querySelector('button[id="btnUpdNotes"]');
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
                            </td>
                            <td class="align-top">{!! $chekJar->last_reason_assessor ?? '-' !!}</td>
                        </tr>
                        <tr>
                            <td class="align-top">
                                <b>Last Note PIC NOS MD</b>
                                @if(Auth::user()->role == 'PIC NOS MD')
                                    @if($chekJar->status === 3)
                                        <br>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editdecision">
                                            <span class="mdi mdi-pen"></span> Update Decision
                                        </button>
                                        <div class="modal fade" id="editdecision" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Decision</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('review.updateDecisionPIC', encrypt($id)) }}" method="post" enctype="multipart/form-data" id="updnotes">
                                                        @csrf
                                                        <input type="hidden" name="idPeriod" value="{{ $period->id }}">
                                                        <div class="modal-body" style="max-height: 65vh; overflow-x:auto;">
                                                            <div class="row px-2">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <h5 class="fw-bold">Decision</h5>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="decision" id="approved" value="2" @if(in_array($chekJar->last_decision_pic, [2])) checked @endif required>
                                                                            <label class="form-check-label">Approved</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="decision" id="notapproved" value="1" @if($chekJar->last_decision_pic == 1) checked @endif>
                                                                            <label class="form-check-label">Reject</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mt-4">
                                                                    <div class="form-group">
                                                                        <h5 class="fw-bold">Note</h5>
                                                                        <textarea id="ckeditor-classic" name="note">{!! $chekJar->last_reason_pic !!}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" id="btnUpdNotes"><i class="fas fa-sync fa-fw" aria-hidden="true"></i> Update</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        document.getElementById('updnotes').addEventListener('submit', function(event) {
                                                            if (!this.checkValidity()) {
                                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                                return false;
                                                            }
                                                            var submitButton = this.querySelector('button[id="btnUpdNotes"]');
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
                            </td>
                            <td class="align-top">
                                @if($chekJar->last_decision_pic == 2)
                                    <span class="badge bg-success text-white">Approved</span><br>
                                @endif
                                @if($chekJar->last_decision_pic == 1)
                                    <span class="badge bg-danger text-white">Reject</span><br>
                                @endif

                                {!! $chekJar->last_reason_pic ?? '-' !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="server-side-table">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Parent Point</th>
                                    <th class="align-middle text-center">Child Point</th>
                                    <th class="align-middle text-center">Sub Point</th>
                                    <th class="align-middle text-center">Detail</th>
                                    <th class="align-middle text-center">Response</th>
                                    <th class="align-middle text-center">Photo</th>
                                    <th class="align-middle text-center">Assessor<br>Decision</th>
                                    @if(Auth::user()->role == 'Assessor Main Dealer')
                                        <th class="align-middle text-center">Action</th>
                                    @endif
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
    var userRole = "{{ Auth::user()->role }}";
    $(function() {
        if(userRole == 'Assessor Main Dealer'){
            var table = $('#server-side-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('review.reviewChecklist', encrypt($id)) !!}',
                // pageLength: 100,
                pageLength: -1, // Show all rows
                lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false,
                        className: 'align-top text-center',
                    },
                    {
                        data: 'parent_point_checklist',
                        name: 'parent_point_checklist',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                    },
                    {
                        data: 'child_point_checklist',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                        render: function(data, type, row) {
                            return row.child_point_checklist
                                ? row.child_point_checklist
                                : '-';
                        },
                    },
                    {
                        data: 'sub_point_checklist',
                        name: 'sub_point_checklist',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                        render: function(data, type, row) {
                            if (data) {
                                var spc = row.sub_point_checklist;
                                var spc = spc.length > 35 ? spc.substr(0, 35) + '...' : spc;
                                return spc;
                            } else {
                                return '';
                            }
                        },
                    },
                    {
                        data: 'detail',
                        name: 'detail',
                        orderable: true,
                        searchable: true,
                        className: 'align-top text-center',
                    },
                    {
                        data: 'response',
                        name: 'response',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                    },
                    {
                        data: 'approve',
                        name: 'approve',
                        orderable: false,
                        searchable: false,
                        className: 'align-top text-center',
                        render: function(data, type, row) {
                            var html
                            if(row.approve == 0){
                                html = '<span class="badge bg-danger text-white">Reject</span>';
                            } else if(row.approve == 1){
                                html = '<span class="badge bg-success text-white">Approve</span>';
                            } else if(row.approve == 2){
                                html = '<span class="badge bg-danger text-white">Rejected</span>';
                            } else if(row.approve == 3){
                                html = '<span class="badge bg-success text-white">Approved</span>';
                            } else {
                                html = '<span class="badge bg-secondary text-white"><i class="mdi mdi-reload label-icon"></i></span>';
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
                    }
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();
                    var lastParent = null;
                    var rowspan = 1;

                    api.column(1, { page: 'current' }).data().each(function(parent, i) {
                        if (lastParent === parent) {
                            rowspan++;
                            $(rows).eq(i).find('td:eq(1)').remove(); // Remove duplicate cells in the `parent_point_checklist` column
                        } else {
                            if (lastParent !== null) {
                                $(rows).eq(i - rowspan).find('td:eq(1)').attr('rowspan', rowspan); // Set rowspan for previous group
                            }
                            lastParent = parent;
                            rowspan = 1;
                        }
                    });

                    // Apply rowspan for the last group
                    if (lastParent !== null) {
                        $(rows).eq(api.column(1, { page: 'current' }).data().length - rowspan).find('td:eq(1)').attr('rowspan', rowspan);
                    }
                }
            });
        } else {
            var table = $('#server-side-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('review.reviewChecklist', encrypt($id)) !!}',
                pageLength: 100,
                columns: [
                    {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false,
                        className: 'align-top text-center',
                    },
                    {
                        data: 'parent_point_checklist',
                        name: 'parent_point_checklist',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                    },
                    {
                        data: 'child_point_checklist',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                        render: function(data, type, row) {
                            return row.child_point_checklist
                                ? row.child_point_checklist
                                : '-';
                        },
                    },
                    {
                        data: 'sub_point_checklist',
                        name: 'sub_point_checklist',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                        render: function(data, type, row) {
                            if (data) {
                                var spc = row.sub_point_checklist;
                                var spc = spc.length > 35 ? spc.substr(0, 35) + '...' : spc;
                                return spc;
                            } else {
                                return '';
                            }
                        },
                    },
                    {
                        data: 'detail',
                        name: 'detail',
                        orderable: true,
                        searchable: true,
                        className: 'align-top text-center',
                    },
                    {
                        data: 'response',
                        name: 'response',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                    },
                    {
                        data: 'photo',
                        name: 'photo',
                        orderable: true,
                        searchable: true,
                        className: 'align-top',
                    },
                    {
                        data: 'approve',
                        name: 'approve',
                        orderable: false,
                        searchable: false,
                        className: 'align-top text-center',
                        render: function(data, type, row) {
                            var html
                            if(row.approve == 0){
                                html = '<span class="badge bg-danger text-white">Reject</span>';
                            } else if(row.approve == 1){
                                html = '<span class="badge bg-success text-white">Approve</span>';
                            } else if(row.approve == 2){
                                html = '<span class="badge bg-danger text-white">Rejected</span>';
                            } else if(row.approve == 3){
                                html = '<span class="badge bg-success text-white">Approved</span>';
                            } else {
                                html = '<span class="badge bg-secondary text-white"><i class="mdi mdi-reload label-icon"></i></span>';
                            }
                            return html;
                        },
                    },
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();
                    var lastParent = null;
                    var rowspan = 1;

                    api.column(1, { page: 'current' }).data().each(function(parent, i) {
                        if (lastParent === parent) {
                            rowspan++;
                            $(rows).eq(i).find('td:eq(1)').remove(); // Remove duplicate cells in the `parent_point_checklist` column
                        } else {
                            if (lastParent !== null) {
                                $(rows).eq(i - rowspan).find('td:eq(1)').attr('rowspan', rowspan); // Set rowspan for previous group
                            }
                            lastParent = parent;
                            rowspan = 1;
                        }
                    });

                    // Apply rowspan for the last group
                    if (lastParent !== null) {
                        $(rows).eq(api.column(1, { page: 'current' }).data().length - rowspan).find('td:eq(1)').attr('rowspan', rowspan);
                    }
                }
            });
        }
    });
</script>

@endsection