@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        @include('layouts.alert')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h4 class="mb-sm-0 font-size-18">Assigned Period Checklist Auditor</h4>
                                @if (Auth::user()->role == 'Internal Auditor Dealer')
                                    <h6 class="font-weight-bold mt-2">({{ $branchName }})</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="server-side-table">
                            <thead>
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
        $('#server-side-table').DataTable({
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

@if(in_array(Auth::user()->role, ['Super Admin', 'Admin', 'Assessor Main Dealer', 'PIC Dealers', 'PIC NOS MD']))
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
                var table = $("#server-side-table").DataTable();
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
            $('#filterBranch').on('change', function() { $("#server-side-table").DataTable().ajax.reload(); });
        });
    </script>
@endif

@endsection