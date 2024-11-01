@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        @include('layouts.alert')
        <div class="row">
            <div class="col-12">
                <div class="card">
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

<script>
    $(function() {
        $('#server-side-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('assessor.listperiod.assigned') !!}',
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
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.status == null){
                            html = '<span class="badge bg-warning text-white">Expired</span>';
                        } else if(row.status == 0){
                            html = '<span class="badge bg-danger text-white">Inactive</span>';
                        } else if(row.status == 1){
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else if(row.status == 2){
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else if(row.status == 3){
                            html = '<span class="badge bg-success text-white">Active</span> <span class="badge bg-info text-white">Complete</span>';
                        } else if(row.status == 4){
                            html = '<span class="badge bg-success text-white">Assessor Approved</span>';
                        } else if(row.status == 5){
                            html = '<span class="badge bg-success text-white">Active</span> <span class="badge bg-danger text-white">Rejected</span>';
                        } else if(row.status == 6){
                            html = '<span class="badge bg-success text-white"><i class="mdi mdi-check-underline-circle label-icon"></i> Approved</span>';
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