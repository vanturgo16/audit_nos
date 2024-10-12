@extends('layouts.master')
@section('konten')
<link rel="stylesheet" href="{{ asset('assets/css/style-form.css') }}" type="text/css"/>

<div class="page-content">
    <div class="container-fluid">
        @include('layouts.alert')

        <div class="row" id="buildForm">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Tab Parent -->
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($tabLists as $item)
                                <li class="nav-item">
                                    <a class="nav-link {{ $item['parent_point_checklist'] === $tabParentAct ? 'active' : '' }}" id="tabBtn" dataTab="{{ $item['parent_point_checklist'] }}" dataQuest="{{ $item['firstIdQuestion'] }}">
                                        <button type="button" class="lmt btn btn-sm {{ $item['isFullFilled'] === 1 ? 'btn-success' : 'btn-light' }}" >{{ $item['parent_point_checklist'] }}</button>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Tab -->
                        <div class="tab-content p-2">
                            <h4 class="text-bold">{{ $tabParentAct }}</h4>
                            <!-- Question Number -->
                            <div class="card-header mt-3 p-0">
                                @foreach ($points as $item)
                                    <a id="questBtn" dataTab="{{ $tabParentAct }}" dataQuest="{{ $item->id }}" 
                                        class="btn pt-1 pb-1 mb-2 btn-outline-primary {{ $item->id === $idQuestionAct ? 'active' : '' }} {{ $item->response != null ? 'btn-success' : '' }}">
                                        {{ $loop->iteration }}
                                    </a>
                                @endforeach
                            </div>
                            <!-- Question -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive" style="max-height: 65vh;">
                                        <table class="table custom-table w-100">
                                            <tbody>
                                                <tr>
                                                    <td colspan="3" class="py-1">
                                                        <h4><span class="badge bg-primary text-bold">{{ $question->sub_point_checklist }}</span></h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 60%;">
                                                        <div style="max-height: 25vh; overflow-y: auto; width: 40vw; overflow-x-auto;">
                                                            {!! $question->indikator !!}
                                                        </div>
                                                        @foreach(json_decode($question->mark, true) as $item)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="question" value="{{ $item['meta_name'] }}" {{ $item['meta_name'] === $question->result ? 'checked' : '' }}>
                                                                <label class="form-check-label">{{ $item['meta_name'] }}</label>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                    <td rowspan="2" style="border-left: double 4px black; width: 20%;">
                                                        <div class="row">
                                                            @if($question->path_guide_checklist != null)
                                                                <div class="col-12">
                                                                    <label for="">Gambar Panduan</label>
                                                                    <div class="custom-image-container">
                                                                        <div class="card">
                                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailGuideImg">
                                                                                <img src="{{ url($question->path_guide_checklist) }}" style="width: 100%; height: auto;" onerror="this.onerror=null;this.src='{{ url('path_to_placeholder_image') }}'; this.alt='Image not found';">
                                                                                <div class="custom-overlay">
                                                                                    <div class="custom-text mt-4">Lihat Gambar</div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td rowspan="2" style="width: 20%;">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label>{{ $question->path_input_response ? 'Perbaharui' : 'Upload' }} Gambar Anda</label>
                                                                @if($question->path_input_response)
                                                                    <div class="custom-image-container">
                                                                        <div class="card">
                                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailRspImg">
                                                                                <img src="{{ url($question->path_input_response) }}" class="custom-img-thumbnail" onerror="this.onerror=null;this.src='{{ url('path_to_placeholder_image') }}'; this.alt='Image not found';">
                                                                                <div class="custom-overlay">
                                                                                    <div class="custom-text mt-4">Lihat Gambar</div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <input class="form-control me-auto" type="file" name="file_checklist">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="py-1">
                                                        @if($question->ms == 1 || $question->mg == 1 || $question->mp || 1)
                                                            <button type="button" class="btn btn-sm btn-warning waves-effect btn-label waves-light" disabled>
                                                                <i class="mdi mdi-alert label-icon"></i>Mandatory : 
                                                                @if($question->ms == 1)
                                                                    <span class="badge bg-danger">Silver</span>
                                                                @endif
                                                                @if($question->mg == 1)
                                                                    <span class="badge bg-danger">Gold</span>
                                                                @endif
                                                                @if($question->mp == 1)
                                                                    <span class="badge bg-danger">Platinum</span>
                                                                @endif
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>                                 
                            </div>
                            <!-- Button Nav -->
                            <div class="row">
                                <div class="col-3 text-start">
                                    <a type="button" href="{{ route('formchecklist.typechecklist', encrypt($idPeriod)) }}" 
                                        class="btn btn-sm btn-danger waves-effect waves-light loadButton">
                                        <i class="mdi mdi-close-box label-icon"></i> | Exit
                                    </a>
                                </div>
                                <div class="col-6 d-flex justify-content-center align-items-center">
                                    <div class="btn-group">
                                        <button id="backBtn" dataTab="{{ $tabParentPrev }}" dataQuest="{{ $idQuestionPrev }}" 
                                            type="button" class="btn btn-secondary waves-effect waves-light loadButton"
                                            style="border-top-left-radius: 20px; border-bottom-left-radius: 20px;" {{ $idQuestionPrev ? '' : 'disabled' }}>
                                            <i class="mdi mdi-arrow-left-circle label-icon"></i> | Back
                                        </button>
                                        <span style="margin-right: 10px;"></span>
                                        <button id="nextBtn" dataTab="{{ $tabParentNext }}" dataQuest="{{ $idQuestionNext }}" 
                                            type="button" class="btn btn-primary waves-effect waves-light"
                                            style="border-top-right-radius: 20px; border-bottom-right-radius: 20px;" {{ $idQuestionNext ? '' : 'disabled' }}>
                                            Next | <i class="mdi mdi-arrow-right-circle label-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <button id="finishBtn"
                                        type="submit" class="btn btn-sm btn-success waves-effect waves-light loadButton">
                                        Finish | <i class="mdi mdi-check-circle label-icon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Modal -->
                            @if($question->path_guide_checklist != null)
                                <div class="modal fade" id="detailGuideImg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Gambar Panduan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" style="max-height: 75vh; overflow-x:auto;">
                                                <div class="row">
                                                    <img src="{{ url($question->path_guide_checklist) }}" class="custom-img-thumbnail" onerror="this.onerror=null;this.src='{{ url('path_to_placeholder_image') }}'; this.alt='Image not found';">
                                                </div>
                                            </div>
                                            <div class="modal-footer"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($question->path_input_response != null)
                                <div class="modal fade" id="detailRspImg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Gambar Anda</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" style="max-height: 75vh; overflow-x:auto;">
                                                <div class="row">
                                                    <img src="{{ url($question->path_input_response) }}" class="custom-img-thumbnail" onerror="this.onerror=null;this.src='{{ url('path_to_placeholder_image') }}'; this.alt='Image not found';">
                                                </div>
                                            </div>
                                            <div class="modal-footer"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
