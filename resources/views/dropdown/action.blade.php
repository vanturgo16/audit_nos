<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
        @if($data->is_active == 0)
            <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | Activate</a></li>
        @else
            <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | Deactivate</a></li>
        @endif
    </ul>
</div>

{{-- MODAL --}}
<div class="left-align">
    {{-- Modal Info --}}
    <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Info Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Status :</span></div>
                                <span>
                                    @if($data->is_active == 1)
                                        <span class="badge bg-success text-white">Active</span>
                                    @else
                                        <span class="badge bg-danger text-white">Inactive</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Category :</span></div>
                                <span>
                                    <span>{{ $data->category }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Name Value :</span></div>
                                <span>
                                    <span>{{ $data->name_value }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Code Format :</span></div>
                                <span>
                                    <span>{{ $data->code_format }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Created At :</span></div>
                                <span>
                                    <span>{{ $data->created_at }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Update --}}
    <div class="modal fade" id="update{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dropdown.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-label">Category</label><label style="color: darkred">*</label>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <select class="form-select" name="category" id="category{{ $data->id }}" required>
                                    <option value="" selected>-- Select Category --</option>
                                    <option disabled>──────────</option>
                                    @foreach( $category as $item)
                                        <option value="{{ $item->category }}" @if($data->category == $item->category) selected="selected" @endif> {{ $item->category }} </option>
                                    @endforeach
                                    <option disabled>──────────</option>
                                    <option class="font-weight-bold" value="NewCat">Add New Category</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <input type="text" name="addcategory" id="addcategory{{ $data->id }}" class="form-control" placeholder="Input New Category">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Name Value</label><label style="color: darkred">*</label>
                                <input class="form-control" name="name_value" type="text" value="{{ $data->name_value }}" placeholder="Input Name Value.." required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Code Format</label><label style="color: darkred">*</label>
                                <input class="form-control" name="code_format" type="text" value="{{ $data->code_format }}" placeholder="Input Code Format.." required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light" id="sb-update{{ $data->id }}"><i class="mdi mdi-update label-icon"></i>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Activate --}}
    <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Activate Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dropdown.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Activate</b> This Dropdown?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="sb-activate{{ $data->id }}"><i class="mdi mdi-check-circle label-icon"></i>Activate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Deactivate --}}
    <div class="modal fade" id="deactivate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Deactivate Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dropdown.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Deactivate</b> This Dropdown?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deactivate{{ $data->id }}"><i class="mdi mdi-close-circle label-icon"></i>Deactivate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let idList = "{{ $data->id }}";

    $('#addcategory' + idList).attr("hidden", true);
    $('#category' + idList).on('change', function() {
        var category = $(this).val();

        if(category=="NewCat"){
            $('#addcategory' + idList).removeAttr("hidden");
            $('#addcategory' + idList).attr("required", true);
        }
        else{
            $('#addcategory' + idList).attr("hidden", true);
            $('#addcategory' + idList).attr("required", false);
        }
    });

    $('#formedit' + idList).submit(function(e) {
        if (!$('#formedit' + idList).valid()){
            e.preventDefault();
        } else {
            $('#sb-update' + idList).attr("disabled", "disabled");
            $('#sb-update' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
        }
    });

    $('#formactivate' + idList).submit(function(e) {
        if (!$('#formactivate' + idList).valid()){
            e.preventDefault();
        } else {
            $('#sb-activate' + idList).attr("disabled", "disabled");
            $('#sb-activate' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
        }
    });

    $('#formdeactivate' + idList).submit(function(e) {
        if (!$('#formdeactivate' + idList).valid()){
            e.preventDefault();
        } else {
            $('#sb-deactivate' + idList).attr("disabled", "disabled");
            $('#sb-deactivate' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
        }
    });
});
</script>