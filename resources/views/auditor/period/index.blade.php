@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h4 class="mb-sm-0 font-size-18">Assigned Period Checklist Auditor</h4>
                                <h6 class="font-weight-bold text-primary mt-1">{{ $jaringanDetail->dealer_name }} - {{ $jaringanDetail->type }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped dt-responsive w-100" id="ssTable">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle text-center">No</th>
                                    <th rowspan="2" class="align-middle text-center">Period Checklist</th>
                                    <th colspan="2" class="align-middle text-center">Handle By</th>
                                    <th rowspan="2" class="align-middle text-center">Status</th>
                                    <th rowspan="2" class="align-middle text-center">Action</th>
                                </tr>
                                <tr>
                                    <th class="align-middle text-center">Auditor</th>
                                    <th class="align-middle text-center">Assesor</th>
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
        const statusLabels = {
            0: '<span class="badge bg-secondary text-white"><i class="mdi mdi-play-box-edit-outline label-icon"></i> Initiate</span>',
            1: '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Assigned - Checklist Process</span>',
            2: '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Revision - Checklist Process</span>',
            3: '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review Assessor</span>',
            4: '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review PIC MD</span>',
            5: '<span class="badge bg-success text-white"><i class="mdi mdi-check-all label-icon"></i> Approved - Done</span>',
            default: '<span class="badge bg-secondary text-white">Null</span>'
        };

        $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('auditor.periodList') !!}',
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
                    className: 'align-top text-center',
                },
                {
                    data: 'period',
                    name: 'period',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var startDate = new Date(row.start_date);
                        var endDate = new Date(row.end_date);
                        return '<b>' + row.period + '</b><br>' + startDate.toLocaleDateString('es-CL').replace(/\//g, '-') + '<b> Until </b>' + endDate.toLocaleDateString('es-CL').replace(/\//g, '-');
                    },
                },
                {
                    data: 'auditor_name',
                    name: 'auditor_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return data ?? '-';
                    }
                },
                {
                    data: 'assesor_name',
                    name: 'assesor_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return data ?? '-';
                    }
                },
                {
                    data: 'status',
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
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
                    className: 'align-top text-center',
                },
            ],
        });
    });
</script>

@endsection