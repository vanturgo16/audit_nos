@extends('layouts.master')
@section('konten')


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List Period Checklist - Jaringan ({{$jaringan}})</h4>
                    @if(Auth::user()->role == 'Internal Auditor Dealer')
                    @else
                        <div class="page-title-right">
                            <a id="backButton" type="button" href="{{ route('formchecklist.jaringanList') }}"
                                class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                                <i class="mdi mdi-arrow-left-circle label-icon"></i>
                                Back
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

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
            ajax: '{!! route('formchecklist.periodList', encrypt($id)) !!}',
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
                    className: 'align-middle text-center text-bold',
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
                        if(row.status == null){
                            html = '<span class="badge bg-warning text-white">Expired</span>';
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