<button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}">
    <span class="mdi mdi-information"></span>
</button>
{{-- Modal --}}
<div class="left-align truncate-text">
    <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 65vh; overflow-x:auto;">
                    <div class="row">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Parent Point :</span></div>
                                    <span>
                                        <span>{{ $data->parent_point_checklist }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
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
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Sub Point :</span></div>
                                    <span>
                                        <span>{{ $data->sub_point_checklist }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div><span class="fw-bold">Indikator :</span></div>
                                <span>{!! $data->indikator !!}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <div class="form-group">
                                    <div><span class="fw-bold">Guide Checklist :</span></div>
                                    <span>
                                        @if($data->path_guide_checklist == null)
                                            <span class="badge bg-secondary text-white">Null</span>
                                        @else
                                            <img src="{{ asset($data->path_guide_checklist) }}" class="img-thumbnail" width="200" alt="Thumbnail 1">
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <div class="form-group">
                                    <div><span class="fw-bold">Mandatory :</span></div>
                                    <span>
                                        @if($data->ms == 1)
                                            <span class="badge bg-success text-white">S</span>
                                        @endif
                                        @if($data->mg == 1)
                                            <span class="badge bg-success text-white">G</span>
                                        @endif
                                        @if($data->mp == 1)
                                            <span class="badge bg-success text-white">P</span>
                                        @endif
                                        @if($data->ms != 1 && $data->mg != 1 && $data->mp != 1)
                                            <span class="badge bg-secondary text-white">Null</span>
                                        @endif
                                    </span>
                                </div>
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
</div>
