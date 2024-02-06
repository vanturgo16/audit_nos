@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List Audit Log</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Logs</a></li>
                            <li class="breadcrumb-item active">Audit Log</li>
                        </ol>
                    </div>
                </div>
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
                                    <th class="align-middle text-center">Email</th>
                                    <th class="align-middle text-center">Access From</th>
                                    <th class="align-middle text-center">Activity</th>
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
            ajax: '{!! route('auditlog') !!}',
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
                    orderable: true,
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'ip_address',
                    orderable: true,
                    render: function(data, type, row) {
                        return row.ip_address + ' - <b>' + row.access_from + '</b><br>' + row.created_at;
                    },
                },
                {
                    orderable: true,
                    data: 'activity',
                    name: 'activity'
                },
            ],
        });
    });
</script>

@endsection