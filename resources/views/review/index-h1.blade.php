@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between py-0 mb-3">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('review.periodList') }}">List Period</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('review.periodDetail', encrypt($period->id)) }}">{{ $period->period }}</a></li>
                            <li class="breadcrumb-item active">Review {{ $typeCheck }}</li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('review.periodDetail', encrypt($period->id)) }}"
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
                    $badgeStyle = match ($value) {
                        'Bronze' => 'background-color: #cd7f32; color: white;',
                        'Silver' => 'background-color: #c0c0c0; color: black;',
                        'Gold' => 'background-color: #ffd700; color: black;',
                        'Platinum' => 'background: linear-gradient(135deg, #e5e4e2 0%, #f2f2f2 100%); color: black; text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.6); border: 1px solid #dcdcdc; box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.3);', // Shiny Platinum effect
                        default => 'background-color: #f8f9fa; color: black;',
                    };
                    return "<span class='badge' style='$badgeStyle'>$value</span>";
                }
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
                                        <h3 class="fw-bold text-info">{{ $chekJar->result_percentage ? $chekJar->result_percentage . '%' : '-' }}</h3>
                                    </td>
                                    <td class="align-top px-3">
                                        <h3 class="fw-bold text-success">{{ $chekJar->result_percentage_assesor ? $chekJar->result_percentage_assesor . '%' : '-' }}</h3>
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
                                        <h3>{!! getBadge($chekJar->audit_result) !!}</h3>
                                    </td>
                                    <td class="align-top px-3">
                                        <h3>{!! getBadge($chekJar->audit_result_assesor) !!}</h3>
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
                                        <h3>{!! getBadge($chekJar->mandatory_item) !!}</h3>
                                    </td>
                                    <td class="align-top px-3">
                                        <h3>{!! getBadge($chekJar->mandatory_item_assesor) !!}</h3>
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
                                        <h3>{!! getBadge($chekJar->result_final) !!}</h3>
                                    </td>
                                    <td class="align-top px-3">
                                        <h3>{!! getBadge($chekJar->result_final_assesor) !!}</h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-top">
                                <b>Last Note Assessor</b>
                                @if(Auth::user()->role == 'Assessor Main Dealer')
                                    @if($chekJar->status === 2)
                                        <br>
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
                                                    <form action="{{ route('review.updateNoteChecklist', encrypt($id)) }}" method="post" enctype="multipart/form-data" id="updnotes">
                                                        @csrf
                                                        <div class="modal-body" style="max-height: 65vh; overflow-x:auto;">
                                                            <div class="row px-2">
                                                                <div class="col-12">
                                                                    <textarea id="ckeditor-classic" name="note">{!! $chekJar->last_reason_assessor !!}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" id="btnUpdNotes"><i class="fas fa-sync fa-fw" aria-hidden="true"></i> Update</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        document.getElementById('updnotes').addEventListener('submit', function(event) {
                                                            if (!this.checkValidity()) {
                                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                                return false;
                                                            }
                                                            var submitButton = this.querySelector('button[id="btnUpdNotes"]');
                                                            submitButton.disabled = true;
                                                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                                            return true; // Allow form submission
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="align-top">{!! $chekJar->last_reason_assessor ?? '-' !!}</td>
                        </tr>
                        <tr>
                            <td class="align-top">
                                <b>Last Note PIC NOS MD</b>
                                @if(Auth::user()->role == 'PIC NOS MD')
                                    @if($chekJar->status === 3)
                                        <br>
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
                                                    <form action="{{ route('review.updateDecisionPIC', encrypt($id)) }}" method="post" enctype="multipart/form-data" id="updnotes">
                                                        @csrf
                                                        <input type="hidden" name="idPeriod" value="{{ $period->id }}">
                                                        <div class="modal-body" style="max-height: 65vh; overflow-x:auto;">
                                                            <div class="row px-2">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <h5 class="fw-bold">Decision</h5>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="decision" id="approved" value="2" @if(in_array($chekJar->last_decision_pic, [2])) checked @endif required>
                                                                            <label class="form-check-label">Approved</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="decision" id="notapproved" value="1" @if($chekJar->last_decision_pic == 1) checked @endif>
                                                                            <label class="form-check-label">Reject</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mt-4">
                                                                    <div class="form-group">
                                                                        <h5 class="fw-bold">Note</h5>
                                                                        <textarea id="ckeditor-classic" name="note">{!! $chekJar->last_reason_pic !!}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary" id="btnUpdNotes"><i class="fas fa-sync fa-fw" aria-hidden="true"></i> Update</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        document.getElementById('updnotes').addEventListener('submit', function(event) {
                                                            if (!this.checkValidity()) {
                                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                                return false;
                                                            }
                                                            var submitButton = this.querySelector('button[id="btnUpdNotes"]');
                                                            submitButton.disabled = true;
                                                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                                            return true; // Allow form submission
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="align-top">
                                @if($chekJar->last_decision_pic == 2)
                                    <span class="badge bg-success text-white">Approved</span><br>
                                @endif
                                @if($chekJar->last_decision_pic == 1)
                                    <span class="badge bg-danger text-white">Reject</span><br>
                                @endif

                                {!! $chekJar->last_reason_pic ?? '-' !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection