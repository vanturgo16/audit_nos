@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('parentchecklist.index') }}">List Parent Checklist</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('parentchecklist.index') }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <form action="{{ route('parentchecklist.update', encrypt($parent->id)) }}" id="formupdate" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center py-3">
                            <h5 class="mb-0">Edit</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Type Checklist</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" name="type_checklist" required>
                                        <option value="" selected>-- Select Type --</option>
                                        <option disabled>──────────</option>
                                        @foreach($type_checklist as $item)
                                            <option value="{{ $item->name_value }}" @if($parent->type_checklist == $item->name_value) selected="selected" @endif> {{ $item->name_value }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                </div>
                                <div class="col-lg-6 mb-3" id="newParent">
                                    <label class="form-label">Parent Point</label><label style="color: darkred">*</label>
                                    <input type="text" name="add_parent" class="form-control" placeholder="Input New Parent" value="{{ $parent->parent_point_checklist }}">
                                </div>
                                <div class="col-lg-6 mb-3">
                                </div>
                                <div class="col-lg-12">
                                    <label class="form-label">Tumbnail</label>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">File Before :</label>
                                    <br>
                                    <span>
                                        <a href="{{ url($parent->path_guide_premises) }}" type="button" class="btn btn-info waves-effect btn-label waves-light" target="_blank">
                                            <i class="mdi mdi-eye label-icon"></i> Show
                                        </a>
                                    </span>
                                </div>
                                <div class="col-lg-6 mb-3" id="newTumbnail">
                                    <label class="form-label">Update</label>
                                    <input type="file" name="thumbnail" accept="image/png, image/jpeg, image/jpg" class="form-control" placeholder="Update Tumbnail">
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
                            </div>
                            
                            <hr>
                            <div class="row">
                                <div class="col-12 align-right">
                                    <a href="{{ route('parentchecklist.index') }}" type="button" class="btn btn-light waves-effect btn-label waves-light">
                                        <i class="mdi mdi-arrow-left-circle label-icon"></i>Back
                                    </a>
                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb">
                                        <i class="mdi mdi-update label-icon"></i>Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Validation Form --}}
<script>
    document.getElementById('formupdate').addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            return false;
        }
        var submitButton = this.querySelector('button[name="sb"]');
        submitButton.disabled = true;
        submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
        return true;
    });
</script>

@endsection