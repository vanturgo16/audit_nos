@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-4">
                                <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Period Checklist</button>
                                {{-- Modal Add --}}
                                <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Add New Period Checklist</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="formLoad" action="{{ route('periodchecklist.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12 mb-3">
                                                            <label class="form-label">Period Checklist</label><label style="color: darkred">*</label>
                                                            <select class="form-select js-example-basic-single" name="period" style="width: 100%" required>
                                                                <option value="" selected>-- Select Period --</option>
                                                                @foreach($period_name as $item)
                                                                    <option value="{{ $item->period_name }}">
                                                                        {{ $item->period_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-12 mb-3">
                                                            <label class="form-label"><i>Jaringan</i> Name (Designated for this)</label><label style="color: darkred">*</label>
                                                            <select class="form-select js-example-basic-single" name="id_branch" style="width: 100%" required>
                                                                <option value="" selected>-- Select Jaringan --</option>
                                                                @foreach($branchs as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->dealer_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 mb-3">
                                                            <label class="form-label">Start Date</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="start_date" type="date" value="" required>
                                                        </div>
                                                        <div class="col-lg-6 mb-3">
                                                            <label class="form-label">End Date</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="end_date" type="date" value="" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                                                        <i class="mdi mdi-plus-box label-icon"></i>Add
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 d-flex justify-content-center align-items-center">
                                <h4 class="mb-sm-0 font-size-18">Master Period Checklist</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped dt-responsive w-100" id="ssTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Period Checklist</th>
                                    <th class="align-middle text-center">Jaringan</th>
                                    <th class="align-middle text-center">Status</th>
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

<script>
    $(function() {
        $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('periodchecklist.index') !!}',
                type: 'GET',
                data: function(d) {
                    d.filterBranch = $('#filterBranch').val(); 
                }
            },
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
                    data: 'period',
                    name: 'period',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle',
                    render: function(data, type, row) {
                        var startDate = new Date(row.start_date);
                        var endDate = new Date(row.end_date);
                        return '<b>' + row.period + '</b><br>' + startDate.toLocaleDateString('es-CL').replace(/\//g, '-') + '<b> Until </b>' + endDate.toLocaleDateString('es-CL').replace(/\//g, '-');
                    },
                },
                {
                    data: 'dealer_name',
                    name: 'dealer_name',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return '<b>' + row.dealer_name + '</b><br>(' + row.type +')';
                    }
                },
                {
                    data: 'status',
                    orderable: true,
                    className: 'align-middle',
                    render: function(data, type, row) {
                        const statusLabels = {
                            0: '<span class="badge bg-secondary text-white"><i class="mdi mdi-play-box-edit-outline label-icon"></i> Initiate</span>',
                            1: '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Assigned - Checklist Process</span>',
                            2: '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Revision - Checklist Process</span>',
                            3: '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review Assessor</span>',
                            4: '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review PIC MD</span>',
                            5: '<span class="badge bg-success text-white"><i class="mdi mdi-check-all label-icon"></i> Approved - Done</span>',
                            default: '<span class="badge bg-secondary text-white">Null</span>'
                        };

                        if (row.is_active == 1) {
                            return statusLabels[row.status] || statusLabels.default;
                        } else {
                            return '<span class="badge bg-warning text-white"><i class="mdi mdi-timer-alert-outline label-icon"></i> Expired</span>';
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

<script>
    $(function() {
        // Hide Length Datatable
        $('.dataTables_wrapper .dataTables_length').hide();

        // Filter Subscription JS
        var lengthDropdown = `
            <label>
                <select id="lengthDT">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </label>
        `;
        $('.dataTables_length').before(lengthDropdown);
        $('#lengthDT').select2({ minimumResultsForSearch: Infinity, width: '60px' });
        $('#lengthDT').on('change', function() {
            var newLength = $(this).val();
            var table = $("#ssTable").DataTable();
            table.page.len(newLength).draw();
        });

        // Filter Branch
        let branchs = @json($branchs);
        var filterBranch = `
            <label>
                <select id="filterBranch">
                    <option value="">-- All Jaringan --</option>
                    ${branchs.map(branch => `<option value="${branch.id}">${branch.dealer_name}</option>`).join('')}
                </select>
            </label>
        `;
        $('.dataTables_length').before(filterBranch);
        $('#filterBranch').select2({width: '300px' });
        $('#filterBranch').on('change', function() { $("#ssTable").DataTable().ajax.reload(); });
    });
</script>
@endsection