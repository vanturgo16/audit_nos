@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="text-center">
                            <h5><b>Period Checklist Beside List Jaringan</b></h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="server-side-table">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Jaringan Name</th>
                                    <th class="align-middle text-center">Last Period Status</th>
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
            ajax: '{!! route('assessor.listjaringan') !!}',
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                },
                {
                    data: 'dealer_name',
                    name: 'dealer_name',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return '<b>' + row.dealer_name + '</b><br>' + row.type;
                    }
                },
                {
                    data: 'latest_period',
                    name: 'latest_period',
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        var html;
                        if(row.latest_period){
                            if (row.latest_status == 1){
                                status = '<span class="badge bg-info text-white">Assigned</span>';
                            } else if (row.latest_status == 2){
                                status = '<span class="badge bg-info text-white">Assigned</span>';
                            } else {
                                status = '<span class="badge bg-info text-white">Assigned</span>';
                            }
                            html = row.latest_period + '<br>' + status;
                        } else {
                            html = '-';
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
                {
                    data: 'type',
                    name: 'type',
                    searchable: true,
                    visible: false
                },
            ],
        });
    });
</script>
@endsection