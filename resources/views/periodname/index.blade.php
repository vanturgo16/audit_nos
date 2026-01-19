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
                                @if(in_array(Auth::user()->role, ['Super Admin', 'Admin', 'PIC Dealers']))
                                <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Period Name</button>
                                {{-- Modal Add --}}
                                <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Add New</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="formLoad" action="{{ route('periodname.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <input class="form-control" name="category" type="hidden" value="Period Name">
                                                        <input class="form-control" name="code_format" type="hidden" value="PN">
                                                        <div class="col-lg-12 mb-3">
                                                            <label class="form-label">Period Name</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="period_name" type="text" value="" placeholder="Input Name Period.." required>
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
                                @endif
                            </div>
                            <div class="col-4 d-flex justify-content-center align-items-center">
                                <h4 class="mb-sm-0 font-size-18">Master Period Name</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped dt-responsive w-100" id="ssTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Period Name</th>
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
        $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('periodname.index') !!}',
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
                    orderable: true,
                    data: 'period_name',
                    name: 'period_name',
                    className: 'align-top text-bold',
                },
                {
                    orderable: true,
                    data: 'status',
                    name: 'status',
                    className: 'align-top',
                    render: function (data, type, row) {
                        if (data == 1) {
                            return `
                            <span class="badge bg-secondary text-white">
                                <i class="mdi mdi-lock-outline me-1"></i>In Use
                            </span>
                            `;
                        }
                        return `
                                <span class="badge bg-info text-white">
                                    <i class="mdi mdi-file-edit-outline me-1"></i>Available Edit
                                </span>
                        `;
                    }
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