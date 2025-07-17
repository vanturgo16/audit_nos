<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between py-0 mb-3">
            <div class="page-title-left">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('listassigned.periodList') }}">List Assigned Period</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('listassigned.periodDetail', encrypt($period->id)) }}">{{ $period->period }}</a></li>
                    <li class="breadcrumb-item active">{{ $typeCheck }}</li>
                </ol>
            </div>
            <div class="page-title-right">
                <a id="backButton" type="button" href="{{ route('listassigned.periodDetail', encrypt($period->id)) }}"
                    class="btn btn-sm btn-secondary waves-effect btn-label waves-light"><i class="mdi mdi-arrow-left-circle label-icon"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    @php
        function getBadge($value) {
            if (!$value) return '-';
            $baseClass = "badge rounded px-2 py-1";
            switch ($value) {
                case 'Bronze':
                    $style = 'background-color: #cd7f32; color: white;';
                    break;
                case 'Silver':
                    $style = 'background-color: #c0c0c0; color: black;';
                    break;
                case 'Gold':
                    $style = 'background-color: #ffd700; color: black;';
                    break;
                case 'Platinum':
                    $style = 'background-color: #e5e4e2; color: black;';
                    return "
                        <div class='badge-wrapper d-inline-block position-relative' style='margin-top: -0.3rem; margin-bottom: -0.3rem;'>
                            <span class='$baseClass' style='$style'>$value</span>
                            <span class='shine-overlay'></span>
                        </div>
                    ";
                default:
                    $style = 'background-color: #f8f9fa; color: black;';
            }
            return "<span class='$baseClass' style='$style'>$value</span>";
        }

        $idUser = Auth::user()->id;
        $role = Auth::user()->role;
        $status = $checkJar->status;
        $idAssesor = $period->id_assesor;
        $isReviewer = in_array($role, ['Assessor Main Dealer', 'PIC NOS MD']);
        $isAssesorReview = $role == 'Assessor Main Dealer' && $status === 2 && $idUser == $idAssesor;
        $isPICReview = $role == 'PIC NOS MD' && $status === 3;
    @endphp
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header text-center p-0">
                <h6 class="fw-bold mt-2">Result</h6>
            </div>
            <div class="card-body p-0">
                <table class="mb-0 w-100" style="border-collapse: separate;">
                    <tbody>
                        <tr>
                            <th class="align-top px-3 w-50" style="border-right: 0.5px solid;"><small>Before</small></th>
                            <th class="align-top px-3 w-50"><small>After</small></th>
                        </tr>
                        <tr>
                            <td class="align-top px-3" style="border-right: 0.5px solid;">
                                <h3 class="fw-bold text-info">{{ $checkJar->result_percentage ? $checkJar->result_percentage . '%' : '-' }}</h3>
                            </td>
                            <td class="align-top px-3">
                                <h3 class="fw-bold text-success">{{ $checkJar->result_percentage_assesor ? $checkJar->result_percentage_assesor . '%' : '-' }}</h3>
                            </td>
                        </tr>
                    </tbody>
                </table>                        
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header text-center p-0">
                <h6 class="fw-bold mt-2">Result Audit</h6>
            </div>
            <div class="card-body p-0">
                <table class="mb-0 w-100" style="border-collapse: separate;">
                    <tbody>
                        <tr>
                            <th class="align-top px-3 w-50" style="border-right: 0.5px solid;"><small>Before</small></th>
                            <th class="align-top px-3 w-50"><small>After</small></th>
                        </tr>
                        <tr>
                            <td class="align-top px-3" style="border-right: 0.5px solid;">
                                <h3>{!! getBadge($checkJar->audit_result) !!}</h3>
                            </td>
                            <td class="align-top px-3">
                                <h3>{!! getBadge($checkJar->audit_result_assesor) !!}</h3>
                            </td>
                        </tr>
                    </tbody>
                </table>                        
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header text-center p-0">
                <h6 class="fw-bold mt-2">Mandatory Item</h6>
            </div>
            <div class="card-body p-0">
                <table class="mb-0 w-100" style="border-collapse: separate;">
                    <tbody>
                        <tr>
                            <th class="align-top px-3 w-50" style="border-right: 0.5px solid;"><small>Before</small></th>
                            <th class="align-top px-3 w-50"><small>After</small></th>
                        </tr>
                        <tr>
                            <td class="align-top px-3" style="border-right: 0.5px solid;">
                                <h3>{!! getBadge($checkJar->mandatory_item) !!}</h3>
                            </td>
                            <td class="align-top px-3">
                                <h3>{!! getBadge($checkJar->mandatory_item_assesor) !!}</h3>
                            </td>
                        </tr>
                    </tbody>
                </table>                        
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header text-center p-0">
                <h6 class="fw-bold mt-2">Result Final</h6>
            </div>
            <div class="card-body p-0">
                <table class="mb-0 w-100" style="border-collapse: separate;">
                    <tbody>
                        <tr>
                            <th class="align-top px-3 w-50" style="border-right: 0.5px solid;"><small>Before</small></th>
                            <th class="align-top px-3 w-50"><small>After</small></th>
                        </tr>
                        <tr>
                            <td class="align-top px-3" style="border-right: 0.5px solid;">
                                <h3>{!! getBadge($checkJar->result_final) !!}</h3>
                            </td>
                            <td class="align-top px-3">
                                <h3>{!! getBadge($checkJar->result_final_assesor) !!}</h3>
                            </td>
                        </tr>
                    </tbody>
                </table>                        
            </div>
        </div>
    </div>

    @if($checkJar->last_correction_assessor === 0 && $idUser == $period->id_assesor)
        <div class="col-12 mb-4">
            <button type="button" class="btn btn-success waves-effect btn-label waves-light float-end" data-bs-toggle="modal" data-bs-target="#recalculate">
                <i class="mdi mdi-sync label-icon"></i>Re-Calculate Result Correction
            </button>
            <div class="modal fade" id="recalculate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-top" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Calculate Correction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="formLoad" action="{{ route('review.syncResultCorrection', encrypt($checkJar->id)) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <h1><span class="mdi mdi-archive-sync" style="color: #FFA500;"></span></h1>
                                        <h5>Re-Calculate Result Correction?</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-sync label-icon"></i>Calculate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-lg-12">
        <table class="table table-bordered dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <td colspan="2" class="align-top fw-bold">Last General Note</td>
                    @if($isReviewer)
                        <td class="align-top text-center fw-bold">Action</td>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="align-top fw-bold" style="white-space: nowrap; width: 1%;">Assessor</td>
                    <td class="align-top">{!! $checkJar->last_reason_assessor ?? '-' !!}</td>
                    @if($isReviewer)
                        <td class="align-top text-center" style="white-space: nowrap; width: 1%;">
                            @if($isAssesorReview)
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editnote">
                                    <span class="mdi mdi-pen"></span> Edit Note
                                </button>
                                <div class="modal fade" id="editnote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Edit Note</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="formLoad" action="{{ route('review.updateNoteChecklist', encrypt($checkJar->id)) }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body" style="max-height: 65vh; overflow-x:auto;">
                                                    <div class="row px-2">
                                                        <div class="col-12">
                                                            <textarea id="ckeditor-classic" name="note">{!! $checkJar->last_reason_assessor !!}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="fas fa-sync label-icon"></i>Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                    @endif
                </tr>
                <tr>
                    <td class="align-top fw-bold" style="white-space: nowrap; width: 1%;">PIC NOS MD</td>
                    <td class="align-top">
                        @if($checkJar->last_decision_pic == 2)
                            <span class="badge bg-success text-white">Approved</span><br>
                        @endif
                        @if($checkJar->last_decision_pic == 1)
                            <span class="badge bg-danger text-white">Reject</span><br>
                        @endif

                        {!! $checkJar->last_reason_pic ?? '-' !!}
                    </td>
                    @if($isReviewer)
                        <td class="align-top text-center" style="white-space: nowrap; width: 1%;">
                            @if($isPICReview)
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editdecision">
                                    <span class="mdi mdi-pen"></span> Update Decision
                                </button>
                                <div class="modal fade" id="editdecision" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Decision</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="formLoad" action="{{ route('review.updateDecisionPIC', encrypt($checkJar->id)) }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="idPeriod" value="{{ $period->id }}">
                                                <div class="modal-body text-start" style="max-height: 65vh; overflow-x:auto;">
                                                    <div class="row px-2">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <h5 class="fw-bold">Decision</h5>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="decision" id="approved" value="2" @if(in_array($checkJar->last_decision_pic, [2])) checked @endif required>
                                                                    <label class="form-check-label">Approved</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="decision" id="notapproved" value="1" @if($checkJar->last_decision_pic == 1) checked @endif>
                                                                    <label class="form-check-label">Reject</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mt-4">
                                                            <div class="form-group">
                                                                <h5 class="fw-bold">Note</h5>
                                                                <textarea id="ckeditor-classic" name="note">{!! $checkJar->last_reason_pic !!}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="fas fa-sync label-icon"></i>Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>