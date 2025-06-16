@extends('layouts.master')
@section('konten')

@include('employee.indexmodal')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Master Employee</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Employee</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- @include('layouts.alert') --}}

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New employee</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive w-100" id="server-side-table" style="font-size: small">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Name</th>
                                    <th class="align-middle text-center">Email</th>
                                    <th class="align-middle text-center">Phone</th>
                                    <th class="align-middle text-center">Dealer / Jaringan</th>
                                    <th class="align-middle text-center">Position</th>
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
            ajax: '{!! route('employee.index') !!}',
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
                    data: 'employee_name',
                    name: 'employee_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-bold',
                },
                {
                    data: 'email',
                    name: 'email',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-bold',
                },
                {
                    data: 'employee_telephone',
                    name: 'employee_telephone',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle',
                },
                {
                    data: 'dealer_name',
                    name: 'dealer_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-bold',
                },
                {
                    data: 'position_name',
                    name: 'position_name',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle',
                    render: function(data, type, row) {
                        return row.department_name + '<br>' + row.position_name;
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