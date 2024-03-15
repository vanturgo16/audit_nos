@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List Period</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Assessor</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">List Jaringan</a></li>
                            <li class="breadcrumb-item active">List Period</li>
                        </ol>
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
                            <td class="align-middle"><b><i>Jaringan</i> Name</b></td>
                            <td class="align-middle">: {{ $jaringan->dealer_name }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b><i>Jaringan</i> Type</b></td>
                            <td class="align-middle">: {{ $jaringan->type }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="server-side-table">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Period Checklist</th>
                                    <th class="align-middle text-center">Date</th>
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
            ajax: '{!! route('assessor.listperiod', encrypt($jaringan->id)) !!}',
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
                    className: 'align-middle text-bold'
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var startDate = new Date(row.start_date);
                        var endDate = new Date(row.end_date);
                        return startDate.toLocaleDateString('es-CL').replace(/\//g, '-') + '<b> Until </b>' + endDate.toLocaleDateString('es-CL').replace(/\//g, '-');
                    },

                },
                {
                    data: 'status',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.status == 0){
                            html = '<span class="badge bg-danger text-white">Inactive</span>';
                        } else if(row.status == 1){
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else if(row.status == 2){
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else if(row.status == 3){
                            html = '<span class="badge bg-success text-white">Active</span> <span class="badge bg-info text-white">Complete</span>';
                        } else if(row.status == 4){
                            html = '<span class="badge bg-danger text-white">Closed Approved</span>';
                        } else if(row.status == 5){
                            html = '<span class="badge bg-success text-white">Active</span>';
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