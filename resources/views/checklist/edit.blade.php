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
                            <li class="breadcrumb-item active">Edit</li>
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
        
        @include('layouts.alert')

        <form action="{{ route('checklist.update', encrypt($checklist->id)) }}" id="formupdate" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center py-3">
                            <h5 class="mb-0">Edit</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <input type="hidden" name="type_checklist_before" value="{{ $parent->type_checklist }}">
                                <div class="col-lg-6">
                                    <label class="form-label">Type Checklist</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_checklist" required>
                                        <option value="" selected>-- Select Type --</option>
                                        @foreach($type_checklist as $item)
                                            <option value="{{ $item->name_value }}" @if($parent->type_checklist == $item->name_value) selected="selected" @endif> {{ $item->name_value }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Parent Point</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="parent_point_checklist" id="parentPoint" required>
                                        <option value="" selected>-- Select Parent --</option>
                                        @foreach( $type_parent as $item)
                                            <option value="{{ $item->id }}" @if($checklist->id_parent_checklist == $item->id) selected="selected" @endif> {{ $item->parent_point_checklist }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="guidechceklist">
                                <div class="col-6">
                                    <label class="form-label">{{ $checklist->path_guide_checklist ? 'Update' : 'Upload' }} Guide Checklist (H1 Premises)</label>
                                    @if($checklist->path_guide_checklist != null)
                                        -> 
                                        <a href="{{ url($checklist->path_guide_checklist) }}" target="_blank">
                                            <u>Show File Before</u>
                                        </a>
                                    @endif
                                    <input type="file" name="guide_checklist" accept="image/png, image/jpeg, image/jpg" class="form-control" placeholder="Input Guide">
                                </div>
                            </div>
                            <script>
                                var type = '{{ $parent->type_checklist }}';
                                var path = '{{ $checklist->path_guide_checklist }}';
                                if (type === 'H1 Premises') {
                                    $('#guidechceklist').show();
                                    if(path){
                                        $('input[name="guide_checklist"]').attr("required", false);
                                    } else {
                                        $('input[name="guide_checklist"]').attr("required", true);
                                    }
                                } else {
                                    $('#guidechceklist').hide();
                                    $('input[name="guide_checklist"]').attr("required", false);
                                }
                                // getParentList
                                $('select[name="type_checklist"]').on('change', function() {
                                    var typeChecklist = $(this).find('option:selected').val();

                                    if (typeChecklist === 'H1 Premises') {
                                        $('#guidechceklist').show();
                                        if(path){
                                            $('input[name="guide_checklist"]').attr("required", false);
                                        } else {
                                            $('input[name="guide_checklist"]').attr("required", true);
                                        }
                                    } else {
                                        $('#guidechceklist').hide();
                                        $('input[name="guide_checklist"]').attr("required", false);
                                    }

                                    var url = '{{ route("mappingParent", ":name") }}';
                                    url = url.replace(':name', typeChecklist);
                                    
                                    if (typeChecklist) {
                                        $.ajax({
                                            url: url,
                                            type: "GET",
                                            dataType: "json",
                                            success: function(data) {
                                                $('select[id="parentPoint"]').empty();
                                                $('select[id="parentPoint"]').append(
                                                    '<option value="" selected>-- Select Parent --</option>'
                                                );

                                                $.each(data, function(div, value) {
                                                    $('select[id="parentPoint"]').append(
                                                        '<option value="' + value.id + '">' + value.parent_point_checklist + '</option>');
                                                });
                                            }
                                        });
                                    } else {
                                        $('select[id="parentPoint"]').empty();
                                    }
                                });
                            </script>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label">Child Point (Optional)</label>
                                    <input class="form-control" name="child_checklist" type="text" value="{{ $checklist->child_point_checklist }}" placeholder="Optional Input Child Point..">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Sub Point</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="sub_point_checklist" type="text" value="{{ $checklist->sub_point_checklist }}" placeholder="Input Sub Point.." required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label">Indikator</label><label style="color: darkred">*</label>
                                    <textarea id="ckeditor-classic" name="indikator">{!! $checklist->indikator !!}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card p-2">
                                        <div class="card-header p-1">
                                            <div class="text-center text-bold">
                                                Mandatory<label style="color: darkred">*</label>
                                            </div>
                                        </div>
                                        <div class="row p-2">
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label d-block">Mandatory Silver</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver1" value="0" required @if($checklist->mandatory_silver == 0) checked @endif>
                                                    <label class="form-check-label" for="mandatory_silver1">No</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver2" value="1" @if($checklist->mandatory_silver == 1) checked @endif>
                                                    <label class="form-check-label" for="mandatory_silver2">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label d-block">Mandatory Gold</label>
                                                
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="mandatory_gold" id="mandatory_gold1" value="0" required @if($checklist->mandatory_gold == 0) checked @endif>
                                                    <label class="form-check-label" for="mandatory_gold1">No</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="mandatory_gold" id="mandatory_gold2" value="1" @if($checklist->mandatory_gold == 1) checked @endif>
                                                    <label class="form-check-label" for="mandatory_gold2">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label d-block">Mandatory Platinum</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="mandatory_platinum" id="mandatory_platinum1" value="0" required @if($checklist->mandatory_platinum == 0) checked @endif>
                                                    <label class="form-check-label" for="mandatory_platinum1">No</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="mandatory_platinum" id="mandatory_platinum2" value="1"  @if($checklist->mandatory_platinum == 1) checked @endif>
                                                    <label class="form-check-label" for="mandatory_platinum2">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            <div class="row">
                                <div class="col-12 align-right">
                                    <a href="{{ route('checklist.index', $parent->type_checklist) }}" type="button" class="btn btn-light waves-effect btn-label waves-light">
                                        <i class="mdi mdi-arrow-left-circle label-icon"></i>Back
                                    </a>
                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb">
                                        <i class="mdi mdi-update label-icon"></i>Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Validation Form --}}
<script>
    document.getElementById('formupdate').addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            return false;
        }
        var submitButton = this.querySelector('button[name="sb"]');
        submitButton.disabled = true;
        submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
        return true;
    });
</script>

@endsection