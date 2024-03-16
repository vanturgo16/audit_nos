<button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#info{{ $data->id_assign }}">
    <span class="mdi mdi-information"></span> | Detail
</button>

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Info --}}
    <div class="modal fade" id="info{{ $data->id_assign }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
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
                                    <span>{{ $data->parent_point }}</span>
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
                        <div class="col-lg-6 mb-2">
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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Image :</span></div>
                                <span>
                                    @if($data->path_guide_premises == null)
                                        <span class="badge bg-secondary text-white">Null</span>
                                    @else
                                        <img src="{{url($data->path_guide_premises)}}" class="img-thumbnail" width="200" alt="Thumbnail 1">
                                    @endif
                                </span>
                            </div>
                        </div>
                        @php
                            $file = "";
                            foreach($file_point as $file){
                                if($data->parent_point == $file->parent_point){
                                    $file = $file->path_url;
                                    break;
                                }else{
                                    $file= "";
                                    break;
                                }
                            }
                        @endphp
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Response Upload File :</span></div>
                                <span>
                                    @if($file != "")
                                        <a href="{{ url($file) }}"
                                            type="button" class="btn btn-info waves-effect btn-label waves-light" download="File {{$data->parent_point}}">
                                            <i class="mdi mdi-download label-icon"></i> Download
                                        </a>
                                    @else
                                        <span class="badge bg-secondary text-white">Null</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div><span class="fw-bold">Response :</span></div>
                                <span class="badge bg-success text-white">
                                    {{ $data->response }}
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
</div>