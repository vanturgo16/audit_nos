@extends('layouts.master')

@section('konten')

<style>
    /* Style Image Hover */
    .custom-image-container {
        position: relative;
        width: 100%;
    }
    .custom-image-container:hover .custom-overlay {
        opacity: 1;
    }
    .custom-image-container img {
        width: 100%;
        display: block;
    }
    .custom-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .custom-text {
        color: white;
        font-size: 20px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
</style>

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
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Type Checklist :</span></div>
                                    <span>
                                        <span>{{ $parent->type_checklist }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Parent Point Name :</span></div>
                                    <span>
                                        <span>{{ $parent->parent_point_checklist }}</span>
                                    </span>
                                </div>
                            </div>
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
                                                -
                                            @endif
                                        </span>
                                    </span>
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
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Indikator :</span></div>
                                    <div class="card p-2">
                                        <span>{!! $checklist->indikator !!}</span>
                                    </div>
                                </div>
                            </div>

                            @if($parent->path_guide_premises != null)
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Guide Image :</span></div>
                                        <div class="custom-image-container">
                                            <a href="{{ url($parent->path_guide_premises) }}" target="_blank">
                                                <img src="{{ url($parent->path_guide_premises) }}" class="custom-img-thumbnail" onerror="this.onerror=null;this.src='{{url('path_to_placeholder_image')}}'; this.alt='Image not found';">
                                                <div class="custom-overlay">
                                                    <div class="custom-text">View Full Image</div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-6 mb-3"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection