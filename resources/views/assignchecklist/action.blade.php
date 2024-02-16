<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id_assign_checklist }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id_assign_checklist }}">
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id_assign_checklist }}"><span class="mdi mdi-information"></span> | Info</a></li>
        <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete{{ $data->id_assign_checklist }}"><span class="mdi mdi-delete-alert"></span> | Delete</a></li>
    </ul>
</div>

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Info --}}
    <div class="modal fade" id="info{{ $data->id_assign_checklist }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Type Checklist :</span></div>
                                <span>
                                    <span>{{ $data->type_checklist }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Parent Point :</span></div>
                                <span>
                                    <span>{{ $data->parent_point_checklist }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Child Point :</span></div>
                                <span>
                                    @if($data->child_point_checklist == null)
                                        <span class="badge bg-secondary text-white">Null</span>
                                    @else
                                        <span>{{ $data->child_point_checklist }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Sub Point :</span></div>
                                <span>
                                    <span>{{ $data->sub_point_checklist }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div><span class="fw-bold">Indikator :</span></div>
                            <span>{!! $data->indikator !!}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Mandatory :</span></div>
                                <span>
                                    @if($data->mandatory_silver == 1)
                                        <span class="badge bg-success text-white">S</span>
                                    @endif
                                    @if($data->mandatory_gold == 1)
                                        <span class="badge bg-success text-white">G</span>
                                    @endif
                                    @if($data->mandatory_platinum == 1)
                                        <span class="badge bg-success text-white">P</span>
                                    @endif
                                    @if($data->mandatory_silver != 1 && $data->mandatory_gold != 1 && $data->mandatory_platinum != 1)
                                        <span class="badge bg-secondary text-white">Null</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">File Upload :</span></div>
                                <span>
                                    @if($data->upload_file == 1)
                                        <span class="badge bg-success text-white">Yes</span>
                                    @else
                                        <span class="badge bg-danger text-white">No</span>
                                    @endif
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

    
    {{-- Modal Delete --}}
    <div class="modal fade" id="delete{{ $data->id_assign_checklist }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('assignchecklist.delete', encrypt($data->id_assign_checklist)) }}" id="formdelete{{ $data->id_assign_checklist }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">Are You Sure to Delete this?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-delete{{ $data->id_assign_checklist }}"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        let idList = "{{ $data->id_assign_checklist }}";
                        $('#formdelete' + idList).submit(function(e) {
                            if (!$('#formdelete' + idList).valid()){
                                e.preventDefault();
                            } else {
                                $('#sb-delete' + idList).attr("disabled", "disabled");
                                $('#sb-delete' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>

