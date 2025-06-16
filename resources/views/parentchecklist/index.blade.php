@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('parentchecklist.typechecklist') }}">List Type</a></li>
                            <li class="breadcrumb-item active">List Parent Checklist (Type: {{ $type }})</li>
                        </ol>
                    </div>

                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('parentchecklist.typechecklist') }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- @include('layouts.alert') --}}

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Parent Checklist</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Parent Checklist (Type: {{ $type }})</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('parentchecklist.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <input type="hidden" name="type_checklist" value="{{ $type }}">

                                                <div class="col-lg-6 mb-3" id="newParent">
                                                    <label class="form-label">Parent Point</label><label style="color: darkred">*</label>
                                                    <input type="text" name="add_parent" class="form-control" placeholder="Input New Parent">
                                                </div>
                                                @if(!in_array($type, $typeChecklistPerCheck))
                                                    <div class="col-lg-6 mb-3" id="newTumbnail">
                                                        <label class="form-label">Guide Parent Point</label><label style="color: darkred">*</label>
                                                        <input type="file" name="thumbnail" accept="image/png, image/jpeg, image/jpg" class="form-control" placeholder="Input Tumbnail" required>
                                                        <div id="warningTumb" style="color: red; display: none;">File size exceeds the maximum limit (3 MB). Please choose another file.</div>
                                                    </div>
                                                    <script>
                                                        document.getElementById('newTumbnail').addEventListener('change', function () {
                                                            var input = this.querySelector('input[type="file"]');
                                                            var maxSize = 3 * 1024 * 1024;
                                                            var errorDiv = document.getElementById('warningTumb');

                                                            if (input.files.length > 0) {
                                                                var fileSize = input.files[0].size;

                                                                if (fileSize > maxSize) {
                                                                    errorDiv.style.display = 'block';
                                                                    input.value = ''; // Reset input file
                                                                } else {
                                                                    errorDiv.style.display = 'none';
                                                                }
                                                            }
                                                        });
                                                    </script>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class="badge bg-secondary text-white">automatically get the last order number</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" id="submitButton" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
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
                                    <th class="align-middle text-center">Order No</th>
                                    <th class="align-middle text-center">Parent Point</th>
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
            ajax: '{!! route('parentchecklist.index', $type) !!}',
            columns: [
                {
                    data: 'order_no',
                    name: 'order_no',
                    // render: function(data, type, row, meta) {
                    //     return meta.row + meta.settings._iDisplayStart + 1;
                    // },
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
                {
                    data: 'parent_point_checklist',
                    name: 'parent_point_checklist',
                    orderable: true,
                    className: 'align-middle text-bold'
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