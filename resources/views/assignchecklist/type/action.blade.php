<button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#info{{ $data->idAssCheck }}" title="View Detail">
    <i class="mdi mdi-eye-outline label-icon"></i>
</button>
@if($period->is_active == 1 && $period->status == 0)
    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete{{ $data->idAssCheck }}" title="Delete">
        <i class="mdi mdi-close-circle label-icon"></i>
    </button>
@endif

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Info --}}
    <div class="modal fade" id="info{{ $data->idAssCheck }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Parent Point :</span></div>
                                <span>
                                    <span>{{ $data->parent_point_checklist }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Child Point :</span></div>
                                <span>
                                    @if($data->child_point_checklist == null)
                                        -
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
                    <div class="row mb-3">
                        <div class="col-12">
                            <div><span class="fw-bold">Indikator :</span></div>
                            <span>{!! $data->indikator !!}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Modal Delete --}}
    @if($period->is_active == 1 && $period->status == 0)
        <div class="modal fade" id="delete{{ $data->idAssCheck }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="formLoad" action="{{ route('assignchecklist.delete', encrypt($data->idAssCheck)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <p class="text-center">Are You Sure to Delete this?</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                                <i class="mdi mdi-delete label-icon"></i>Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>
