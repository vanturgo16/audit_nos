@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <a href="{{ url()->previous() }}" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List Assign Checklist
                        </a>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Checklist Audit</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('periodchecklist.index') }}">Period Checklist</a></li>
                            <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Assign Checklists</a></li>
                            <li class="breadcrumb-item active">Preview</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0">Preview Form - <b>{{ $type_checklist }}</b></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection