@if($statusPeriod == 0)
    <span class="badge bg-warning text-white">Expired</span>
@else
    @php
        $role = Auth::user()->role;
        $userId = Auth::id();
        $status = $data->status;
    @endphp

    {{-- Admin --}}
    @if(in_array($role, ['Super Admin', 'Admin', 'PIC Dealer']))
        <a href="{{ route('listassigned.detailChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
    @endif
    
    {{-- Internal Auditor --}}
    @if($role == 'Internal Auditor Dealer')
        @php $isAuditor = $userId == $idAuditor; @endphp

        @if($status == "")
            @if($idAuditor && !$isAuditor)
                -
            @else
                <a type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#start{{ $data->id }}">Start</a>
                {{-- Modal Start --}}
                <div class="left-align truncate-text">
                    <div class="modal fade" id="start{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-top" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Start</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class="formLoad" action="{{ route('auditor.start', encrypt($data->id)) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            @if($today >= $startPeriod)
                                                <div class="col-12 text-center">
                                                    <h1><span class="mdi mdi-play-circle" style="color: #FFA500;"></span></h1>
                                                    <h5>Start This Checklist?</h5>
                                                </div>
                                            @else
                                                <div class="col-12 text-center">
                                                    <h1><span class="mdi mdi-information" style="color: #FFA500;"></span></h1>
                                                    <h5>Checklist Filling Period Hasn't Started Yet</h5>
                                                    <p>Will Be Able To Start On Date <b><u>{{ $startPeriod }}</u></b> Onwards</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        @if($today >= $startPeriod) <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="btnStart{{ $data->id }}"><i class="mdi mdi-play-circle label-icon"></i>Start</button> @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @elseif(in_array($data->status, [0], true))
            {!! $isAuditor ? '<a href="'.route('form.checklist', encrypt($data->id)).'" class="btn btn-sm btn-primary">Check Audit</a>' : '-' !!}
        @elseif(in_array($data->status, [4], true))
            @if($isAuditor)
                <a href="{{ route('form.checklist', encrypt($data->id)) }}" class="btn btn-sm btn-primary mb-1 mr-1">Revisi</a>
                <a type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#note{{ $data->id }}">Open Note</a>
                {{-- Modal Note --}}
                <div class="left-align truncate-text">
                    <div class="modal fade" id="note{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-top" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Note Decision</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <div class="form-group">
                                                <div><span class="fw-bold">Last Note Assessor :</span></div>
                                                <span>
                                                    <span>{!! $data->last_reason_assessor ?? '-' !!}</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-2">
                                            <div class="form-group">
                                                <div><span class="fw-bold">Last Note PIC NOS MD :</span></div>
                                                <span>
                                                    <span>{!! $data->last_reason_pic ?? '-' !!}</span>
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
            @else
                -
            @endif
        @elseif(in_array($data->status, [1, 5], true))
            <a href="{{ route('listassigned.detailChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
        @else 
            -
        @endif
    @endif

    {{-- Assesor --}}
    @if($role == 'Assessor Main Dealer')
        @if($status == 2)
            @php $isAssesor = $userId == $idAssesor; @endphp

            @if($idAssesor)
                @if($isAssesor)
                    <a href="{{ route('listassigned.detailChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-primary">
                        {{ $data->last_correction_assessor === null ? 'Review' : 'Correction' }}
                    </a>
                @else
                    <a href="{{ route('listassigned.detailChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
                @endif
            @else 
                <a href="#" data-bs-toggle="modal" data-bs-target="#takereview{{ $data->id }}" type="button" class="btn btn-sm btn-info">Take Review</a>
                {{-- Modal Take Review --}}
                <div class="modal fade" id="takereview{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-top" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Take Review</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form class="formLoad" action="{{ route('review.takeReview', encrypt($data->id_periode)) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <h1><span class="mdi mdi-pen text-primary"></span></h1>
                                            <h5>Take This Checklist For Review By You?</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" id="btnTakeReview{{ $data->id }}">Ok</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <a href="{{ route('listassigned.detailChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
        @endif
    @endif

    {{-- PIC NOS MD --}}
    @if($role == 'PIC NOS MD')
        @if($status == 3)
            <a href="{{ route('listassigned.detailChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-primary">Review</a>
        @else
            <a href="{{ route('listassigned.detailChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
        @endif
    @endif
@endif

<script src="{{ asset('assets/js/formLoad.js') }}"></script>