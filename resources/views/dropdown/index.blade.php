@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">
                        Master Dropdowns
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">
                                Dropdowns
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Dropdown</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('dropdown.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <label class="form-label">Category</label><label style="color: darkred">*</label>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="category" required>
                                                        <option value="" selected>-- Select Category --</option>
                                                        <option disabled>──────────</option>
                                                        @foreach( $category as $item)
                                                            <option value="{{ $item->category }}" {{ old('category') == $item->category ? 'selected' : '' }}> {{ $item->category }} </option>
                                                        @endforeach
                                                        <option disabled>──────────</option>
                                                        <option class="font-weight-bold" value="NewCat">Add New Category</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <input type="text" name="addcategory" class="form-control" placeholder="Input New Category" required>
                                                </div>
                                                <script type="text/javascript">
                                                    $(document).ready(function () {
                                                        $("input[name='addcategory']").hide();
                        
                                                        $(document.body).on("change.select", "select[name^='category']", function () {
                                                            var category = $(this).val();
                        
                                                            if(category=="NewCat"){
                                                                $("input[name='addcategory']").show();
                                                                $('input[name="addcategory"]').attr("required", true);
                                                            }
                                                            else{
                                                                $("input[name='addcategory']").hide();
                                                                $('input[name="addcategory"]').attr("required", false);
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Name Value</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="name_value" type="text" value="" placeholder="Input Name Value.." required>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Code Format</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="code_format" type="text" value="" placeholder="Input Code Format.." required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
                                        </div>

                                    </form>
                                    <script>
                                        document.getElementById('formadd').addEventListener('submit', function(event) {
                                            if (!this.checkValidity()) {
                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                return false;
                                            }
                                            var submitButton = this.querySelector('button[name="sb"]');
                                            submitButton.disabled = true;
                                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                            return true; // Allow form submission
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive w-100" id="server-side-table">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Category</th>
                                    <th class="align-middle text-center">Name Value</th>
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
            ajax: '{!! route('dropdown.index') !!}',
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
                    data: 'category',
                    name: 'category',
                    className: 'align-middle text-bold',
                },
                {
                    data: 'name_value',
                    name: 'name_value',
                    orderable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'is_active',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html;
                        if (row.is_active == 1) {
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else {
                            html = '<span class="badge bg-danger text-white">Inactive</span>';
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