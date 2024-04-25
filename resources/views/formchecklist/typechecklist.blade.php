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
                                    <span class="badge bg-success text-white">Assessor Approved</span>
                                @elseif($period->status == 5)
                                    <span class="badge bg-success text-white">Active</span> <span class="badge bg-danger text-white">Rejected</span>
                                @elseif($period->status == 6)
                                    <span class="badge bg-success text-white"><i class="mdi mdi-check-underline-circle label-icon"></i> Closed Approved</span>
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
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive w-100" id="server-side-table" style="font-size: small">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Type Checklist</th>
                                    <th class="align-middle text-center">Total Checklist</th>
                                    <th class="align-middle text-center">Checklist Remain</th>
                                    <th class="align-middle text-center">Total Point</th>
                                    <th class="align-middle text-center">% Result</th>
                                    <th class="align-middle text-center">Status</th>
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
    var grading = <?php echo json_encode($grading); ?>;
    $(function() {
        $('#server-side-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('formchecklist.typechecklist', encrypt($id)) !!}',
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
                    className: 'align-middle text-bold',
                },
                {
                    data: 'total_checklist',
                    name: 'total_checklist',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var check = row.total_checklist - row.checklist_remaining;
                        return check + '<b> of </b>' + row.total_checklist;
                    },
                },
                {
                    data: 'checklist_remaining',
                    name: 'checklist_remaining',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'total_point',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.total_point === "" || row.total_point === null) {
                            row.point.forEach(function(point) {
                                html += '<span class="badge bg-info text-white">' + point.type_response + ' : ' + point.count + '</span><br>';
                            });
                            html += '<span class="badge bg-success text-white">Total Point : 0</span><br>';
                        } else {
                            row.point.forEach(function(point) {
                                html += '<span class="badge bg-info text-white">' + point.type_response + ' : ' + point.count + '</span><br>';
                            });
                            html += '<span class="badge bg-success text-white">Total Point : ' + row.total_point + '</span><br>';
                        }
                        return html;
                    },
                },
                {
                    data: 'result_percentage',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html = '';
                        if (row.result_percentage === "" || row.result_percentage === null) {
                            var totalPoint = 0;
                            row.point.forEach(function(point) {
                                if (point.type_response === 'Exist, Good') {
                                    totalPoint += point.count * 1;
                                } else if (point.type_response === 'Exist Not Good') {
                                    totalPoint += point.count * -1;
                                } else if (point.type_response === 'Not Exist') {
                                    totalPoint += point.count * 0;
                                }
                            });
                            var formattedResult = 0;
                            if (totalPoint !== 0) {
                                var result = (totalPoint / (row.total_checklist - row.checklist_remaining)) * 100;
                                formattedResult = result.toFixed(2);
                            }
                            html = formattedResult + ' %';
                        } else {
                            html = row.result_percentage + ' %';
                        }

                        return html;
                    },
                },
                {
                    data: 'status',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html = '';

                        if (row.status === "" || row.status === null) {
                            html = '<span class="badge bg-secondary text-white">Not Started</span>';
                        } else if (row.status == 0) {
                            html = '<span class="badge bg-warning text-white">Not Complete</span>';
                        } else if (row.status == 1) {
                            html = '<span class="badge bg-info text-white">Complete</span>';
                        } else if (row.status == 2) {
                            html = '<span class="badge bg-warning text-white">Reviewed</span>';
                        } else if (row.status == 3) {
                            html = '<span class="badge bg-warning text-white">Reviewed</span>';
                        } else if (row.status == 4) {
                            html = '<span class="badge bg-warning text-white">Reviewed</span>';
                        } else if (row.status == 5) {
                            html = '<button type="button" class="btn btn-sm btn-danger waves-effect btn-label waves-light float-end" data-bs-toggle="modal" data-bs-target="#notapprove'+ row.id +'"><i class="mdi mdi-sim-alert label-icon"></i>Not Approve</button>' +
                                '<div class="modal fade" id="notapprove'+ row.id +'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">' +
                                    '<div class="modal-dialog modal-dialog-top" role="document">' +
                                        '<div class="modal-content">' +
                                            '<div class="modal-header">' +
                                                '<h5 class="modal-title" id="staticBackdropLabel">Info Not Approve</h5>' +
                                                '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                                            '</div>' +
                                            '<div class="modal-body">' +
                                                '<div class="row">' +
                                                    '<div class="col-lg-12">' +
                                                        '<div class="form-group">' +
                                                            '<div><span class="fw-bold">Reason :</span></div>' +
                                                            '<span>' +
                                                                '<span>' + row.last_reason + '</span>' +
                                                            '</span>' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="modal-footer">' +
                                                '<button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>';
                        } else if (row.status == 6) {
                            html = '<span class="badge bg-warning text-white">Reviewed</span>';
                        } else if (row.status == 7) {
                            html = '<span class="badge bg-success text-white">Approve</span>';
                        }

                        return html;
                    },
                },
                {
                    data: 'start_date',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html;
                        if (row.start_date === null) {
                            html = '<span class="badge bg-secondary text-white">Not Started</span>';
                        } else {
                            var startDate = new Date(row.start_date);
                            html = startDate.toLocaleDateString('es-CL').replace(/\//g, '-');
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