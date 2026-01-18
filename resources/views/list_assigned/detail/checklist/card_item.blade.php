<div class="col-12" id="card-{{ $item->id }}">
    @php
        $approve = $item->approve;
        $badgeClass = 'bg-secondary text-white';
        $cardBG = '';
        $cardHeaderBG = '';
        if ($approve === null) {
            $badgeClass = 'bg-secondary text-white';
        } elseif (in_array($approve, [0, 2])) {
            $badgeClass = 'bg-danger text-white';
            $cardBG = 'card-reject';
            $cardHeaderBG = 'card-head-reject';
        } elseif (in_array($approve, [1, 3])) {
            $badgeClass = 'bg-success text-white';
            $cardBG = 'card-approve';
            $cardHeaderBG = 'card-head-approve';
        }

        
        if($statusCorrection === 0 || $statusCorrection === 1){
            if($item->response_correction != $item->response){
                $cardBG = 'card-correction';
                $cardHeaderBG = 'card-head-correction';
            }
        }
        $hasMandatory = ($item->ms == 1 || $item->mg == 1 || $item->mp == 1);
    @endphp

    <div class="card mb-3 {{ $cardBG }}">
        <a href="javascript:void(0);" class="d-flex justify-content-between align-items-center card-header py-3 toggle-collapse {{ $cardHeaderBG }}" 
            data-target="#collapseCard{{ $item->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to show or hide detail">
            <div class="row w-100">
                <div class="col-12 col-md-6 text-start">
                    <div class="row">
                        <div class="col-auto">
                            <h2><span class="badge {{ $badgeClass }}">{{ $index }}</span></h2>
                        </div>
                        <div class="col">
                            <strong>{{ $item->parent_point_checklist ?? '-' }}</strong><br>
                            <small>
                                Child Point: {{ $item->child_point_checklist ?? '-' }}<br>
                                Sub Point: {{ $item->sub_point_checklist ?? '-' }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 text-start mt-2 mt-md-0">
                    <strong>Mandatory</strong><br>
                    @if($hasMandatory)
                        @if($item->ms == 1)
                            <div class="badge-wrapper d-inline-block position-relative">
                                <span class="badge rounded px-2 py-1 position-relative" style="background-color: #ccc; color: #000;">S</span>
                            </div>
                        @endif
                        @if($item->mg == 1)
                            <div class="badge-wrapper d-inline-block position-relative">
                                <span class="badge rounded px-2 py-1" style="background-color: gold; color: #000;">G</span>
                            </div>
                        @endif
                        @if($item->mp == 1)
                            <div class="badge-wrapper d-inline-block position-relative">
                                <span class="badge rounded px-2 py-1" style="background-color: #E5E4E2; color: #000;">P</span>
                                <span class="shine-overlay"></span>
                            </div>
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
                <div class="col-12 col-md-2 text-start mt-2 mt-md-0">
                    @if($approve === null)
                        -
                    @elseif($approve === 0)
                        <h2><span class="badge bg-danger text-white rounded-pill">Reject</span></h2>
                    @elseif($approve === 1)
                        <h2><span class="badge bg-success text-white rounded-pill">Approve</span></h2>
                    @elseif($approve === 2)
                        <h2><span class="badge bg-danger text-white rounded-pill">Rejected</span></h2>
                    @elseif($approve === 3)
                        <h2><span class="badge bg-success text-white rounded-pill">Approved</span></h2>
                    @endif
                </div>
                <div class="col-12 col-md-1 text-end mt-2 mt-md-0">
                    <i class="mdi mdi-chevron-up arrow-icon"></i>
                </div>
            </div>
        </a>
        <div class="collapse show" id="collapseCard{{ $item->id }}">
            <div class="card-body">
                <div class="row w-100">
                    <div class="col-12 col-md-6 text-start">
                        <strong>Indicator Detail</strong><br>
                        <div class="collapsible-text" id="indicator{{ $item->id }}">
                            {!! $item->indikator !!}
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <a href="javascript:void(0);" class="text-primary small toggle-indicator" data-target="#indicator{{ $item->id }}">View More</a>
                            </div>
                            <div class="col-6">
                                @if($perCheck)
                                    <a href="#" class="text-info small" data-bs-toggle="modal" data-bs-target="#guidelineModal{{ $item->id }}">
                                        <u>View Guideline Checklist</u>
                                    </a>
                                @else
                                    @if($item->path_guide_parent)
                                        <a target="_blank" type="button" class="text-info small" href="{{ asset($item->path_guide_parent) }}">
                                            <u>View Guideline Parent</u>
                                        </a>
                                    @else
                                        <a type="button" class="text-muted small" href="#">
                                            No Guideline Parent
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 text-start">
                        @php
                            // $showHistory = true;
                            $showHistory = !empty($item->log_response);
                        @endphp

                        <div class="row">
                            @if($showHistory)
                                <div class="col-auto me-2">
                                    <a href="javascript:void(0)" title="see log history response"
                                        class="btn btn-secondary btn-sm openAjaxModal"
                                        data-id="log_{{ $item->id }}" 
                                        data-size="xl" 
                                        data-url="{{ route('listassigned.logDetail', encrypt($item->id)) }}">
                                        <i class="mdi mdi-history arrow-icon"></i>
                                    </a>
                                </div>
                            @endif

                            <div class="{{ $showHistory ? 'col' : 'col-12' }}">
                                <strong>Response</strong><br>
                                {{ $item->response ?? '-' }}<br>

                                <div class="mt-3">
                                    <strong>{{ $perCheck ? 'Photo' : 'Response File' }}</strong>
                                    @if($item->path_input_response)
                                        @if($perCheck)
                                            <div class="custom-image-container">
                                                <div class="card">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#detailRspFile{{ $item->id }}">
                                                        <img src="{{ asset($item->path_input_response) }}" style="width: 100%; height:auto;" 
                                                            onerror="this.onerror=null;this.src='{{ asset('assets/images/no-image.png') }}'; this.alt='Image not found';">
                                                        <div class="custom-overlay">
                                                            <div class="custom-text mt-4">Lihat Gambar</div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <br>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailRspFile{{ $item->id }}">
                                                View File Response
                                            </button>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 text-start">
                        @if($statusCorrection === 0 || $statusCorrection === 1)
                            <strong>Assesor Correction</strong>
                            @if($statusCorrection == 0 && Auth::user()->id == $idAssesor)
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#correction{{ $item->id }}">
                                    <i class="mdi mdi-pen"></i>
                                </button>
                                <div class="modal fade" id="correction{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Correction Assesor</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start p-4">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @php
                                                            $marks = json_decode($item->mark);
                                                        @endphp
                                                        @foreach($marks as $index => $mark)
                                                            @php
                                                                $markName = trim((string) $mark->meta_name);
                                                                $responseCorrection = trim((string) $item->response_correction);
                                                            @endphp
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" id="option{{ $index }}" name="response_correction_{{ $item->id }}"
                                                                    value="{{ $markName }}" @if($markName === $responseCorrection) checked @endif>
                                                                <label class="form-check-label">{{ $markName }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="col-12 mt-4">
                                                        <label class="form-label">Note Assesor</label>
                                                        <textarea placeholder="Masukkan Catatan..(Opsional)" class="form-control note-assesor" rows="5" required>{{ $item->note_assesor ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary waves-effect btn-label waves-light confirm-correction-btn" data-id="{{ $item->id }}" data-index="{{ $index }}" data-bs-dismiss="modal">
                                                    <i class="mdi mdi-pen label-icon"></i> Correction
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <br>
                            {{ $item->response_correction ?? '-' }}<br>
                        @endif

                        @if(in_array($approve, [null, 0, 1]) && Auth::user()->id == $idAssesor)
                            <strong>Assesor Decision</strong><br>
                            <div id="decision{{ $item->id }}">
                                @if($approve === null)
                                    <a href="#" class="btn btn-sm btn-success waves-effect btn-label waves-light approve-btn" data-id="{{ $item->id }}" data-index="{{ $index }}">
                                        <i class="mdi mdi-check-circle-outline label-icon"></i> Approve
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger waves-effect btn-label waves-light reject-btn" data-bs-toggle="modal" data-bs-target="#reject{{ $item->id }}">
                                        <i class="mdi mdi-close-circle-outline label-icon"></i> Reject
                                    </a>
                                    <div class="modal fade" id="reject{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Notes</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" id="closeModal{{ $item->id }}"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <textarea placeholder="Masukkan Catatan Reject.." class="form-control reject-note" rows="5" required>{{ $item->note_assesor ?? '' }}</textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger waves-effect btn-label waves-light confirm-reject-btn" data-id="{{ $item->id }}" data-index="{{ $index }}" data-bs-dismiss="modal">
                                                        <i class="mdi mdi-close-circle-outline label-icon"></i> Reject
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a href="#" class="btn btn-sm btn-secondary waves-effect btn-label waves-light reset-btn" data-id="{{ $item->id }}" data-index="{{ $index }}">
                                        <i class="mdi mdi-reload label-icon"></i> Reset
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if($item->note_assesor)
                            <div class="mt-3">
                                <strong>Last Assesor Notes</strong>
                            </div>
                            <div class="collapsible-text short" id="notereject{{ $item->id }}">
                                {{ $item->note_assesor ?? '-' }}
                            </div>
                            <a href="javascript:void(0);" class="text-primary small toggle-notes" data-target="#notereject{{ $item->id }}">View More</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Guideline Modal --}}
        @if($item->path_guide_checklist && $perCheck)
            <div class="modal fade" id="guidelineModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Guideline Checklist</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset($item->path_guide_checklist) }}" class="img-fluid" onerror="this.onerror=null;this.src='{{ asset('assets/images/no-image.png') }}';">
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- RESPONSE-->
        @if($item->path_input_response)
            <div class="modal fade" id="detailRspFile{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $perCheck ? 'Photo Response' : 'File Response' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @php
                                $extension = strtolower(pathinfo($item->path_input_response, PATHINFO_EXTENSION));
                                $imageExtensions = ['png', 'jpg', 'jpeg', 'gif'];
                                $videoExtensions = ['mp4', 'webm', 'ogg'];
                                $audioExtensions = ['mp3', 'wav', 'ogg'];
                            @endphp

                            @if ($perCheck || in_array($extension, $imageExtensions))
                                <img src="{{ asset($item->path_input_response) }}" class="img-fluid"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/no-image.png') }}';">
                            @elseif (in_array($extension, $videoExtensions))
                                <video controls class="custom-video-thumbnail">
                                    <source src="{{ asset($item->path_input_response) }}" type="video/{{ $extension }}">
                                    Your browser does not support the video tag.
                                </video>
                            @elseif (in_array($extension, $audioExtensions))
                                <div class="row py-4">
                                    <div class="col-12 text-center">Preview</div>
                                    <div class="col-12 mt-2 text-center">
                                        <audio controls class="custom-audio-thumbnail">
                                            <source src="{{ asset($item->path_input_response) }}" type="audio/{{ $extension }}">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                            @else
                                <div class="row py-4">
                                    <div class="col-12 text-center">Preview Not Available</div>
                                    <div class="col-12 mt-2 text-center">
                                        <a href="{{ asset($item->path_input_response) }}" class="btn btn-primary" download>Download File</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
