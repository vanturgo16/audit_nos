@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('checklist.index', $parent->type_checklist) }}">List Checklist (Type: {{ $parent->type_checklist }})</a></li>
                            <li class="breadcrumb-item active">Info</li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('checklist.index', $parent->type_checklist) }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0">Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="card shadow p-3">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Type Checklist :</span></div>
                                        <span>
                                            <span>{{ $parent->type_checklist }}</span>
                                        </span>
                                    </div>
                                </div>
    
                                <div class="col-lg-6 mb-3"></div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Parent Point Name :</span></div>
                                        <span>
                                            <span>{{ $parent->parent_point_checklist }}</span>
                                        </span>
                                    </div>
                                </div>
                                @if($parent->path_guide_premises != null)
                                    <div class="col-lg-6">
                                        <label class="form-label">File Guide Parent :</label>
                                        <br>
                                        <span>
                                            <a href="{{ url($parent->path_guide_premises) }}" type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light" target="_blank">
                                                <i class="mdi mdi-eye label-icon"></i> Show
                                            </a>
                                        </span>
                                    </div>
                                @else
                                    <div class="col-lg-6"></div>
                                @endif
                            </div>
                        </div>

                        <div class="row px-3">
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Child Point Name :</span></div>
                                    <span>
                                        <span>
                                        @if(empty($checklist->child_point_checklist))
                                            <span class="badge bg-secondary text-white">Not Set</span>
                                        @else
                                            {{ $checklist->child_point_checklist }}
                                        @endif
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Sub Point Name :</span></div>
                                    <span>
                                        <span>{{ $checklist->sub_point_checklist }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Indikator :</span></div>
                                    <span>{!! $checklist->indikator !!}</span>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Mandatory :</span></div>
                                    <span>
                                        <span>@if($checklist->mandatory_silver == 1)
                                                <span class="badge bg-success text-white">S</span>
                                            @endif
                                            @if($checklist->mandatory_gold == 1)
                                                <span class="badge bg-success text-white">G</span>
                                            @endif
                                            @if($checklist->mandatory_platinum == 1)
                                                <span class="badge bg-success text-white">P</span>
                                            @endif
                                            @if(empty($checklist->mandatory_silver) && empty($checklist->mandatory_gold) && empty($checklist->mandatory_platinum))
                                                <span class="badge bg-secondary text-white">Not Set</span>
                                            @endif
                                        </span>
                                    </span>
                                </div>
                            </div>
                            @if($checklist->path_guide_checklist != null)
                                <div class="col-lg-6">
                                    <label class="form-label">File Guide Checklist :</label>
                                    <br>
                                    <span>
                                        <a href="{{ url($checklist->path_guide_checklist) }}" type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light" target="_blank">
                                            <i class="mdi mdi-eye label-icon"></i> Show
                                        </a>
                                    </span>
                                </div>
                            @else
                                <div class="col-lg-6"></div>
                            @endif

                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Mark :</span></div>
                                    <ul>
                                        @foreach($mark as $item)
                                            <li>{{ $item->meta_name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Created At :</span></div>
                                    <span>
                                        <span>{{ $checklist->created_at }}</span>
                                    </span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection