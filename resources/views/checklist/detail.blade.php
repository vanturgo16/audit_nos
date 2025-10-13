@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('checklist.typechecklist') }}">List Type</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('checklist.index', $parent->type_checklist) }}">List Checklist (Type: {{ $parent->type_checklist }})</a></li>
                            <li class="breadcrumb-item active">Manage {{ \Illuminate\Support\Str::limit($checklist->sub_point_checklist, 25, '...') }}</li>
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

        {{-- @include('layouts.alert') --}}
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Parent & Order Number Detail</h5>
                            <a href="#" type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#editParent">
                                <i class="mdi mdi-file-edit label-icon"></i> Edit
                            </a>
                        </div>
                    </div>
                    {{-- Modal Edit --}}
                    <div class="modal fade" id="editParent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Edit Parent & Order Number Detail</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('checklist.updateHeadCheck', encrypt($id)) }}" id="formUpdHeadCheck" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                        <div class="row mb-3">
                                            <input type="hidden" name="type_checklist_before" value="{{ $parent->type_checklist }}">
                                            <div class="col-lg-6">
                                                <label class="form-label">Type Checklist</label><label style="color: darkred">*</label>
                                                <select class="form-select js-example-basic-single" style="width: 100%" name="type_checklist" id="type_checklist" required>
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
                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <input type="hidden" name="order_current" value="{{ $checklist->order_no }}">
                                                <input type="hidden" name="type_checklist_current" value="{{ $parent->type_checklist }}">
                                                <input type="hidden" name="parent_point_checklist_current" value="{{ $checklist->id_parent_checklist }}">
                                                <label class="form-label">Exchange Order Number</label>
                                                <select class="form-select js-example-basic-single" style="width: 100%" name="order_no" id="order_no">
                                                    <option value="0">Change order to First</option>
                                                    <option value="99999">Change order to Last</option>
                                                    @foreach($orders as $order)
                                                        <option value="{{ $order->order_no }}" @if($checklist->order_no == $order->order_no) selected="selected" @endif> {{ $order->order_no . "-" . $order->sub_point_checklist }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6" id="guidechceklist">
                                                <label class="form-label">{{ $checklist->path_guide_checklist ? 'Update' : 'Upload' }} Guide Checklist ({{ $parent->type_checklist }})</label>
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
                                            var typeChecklistPerCheck = @json($typeChecklistPerCheck);

                                            function updateGuideChecklist(typeChecklist) {
                                                if (typeChecklistPerCheck.includes(typeChecklist)) {
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
                                            }
                                            
                                            // Initial check
                                            updateGuideChecklist(type);

                                            // Type Checlist Change
                                            $('select[name="type_checklist"]').on('change', function() {
                                                var selectedType = $(this).val();
                                                updateGuideChecklist(selectedType);

                                                var url = '{{ route("mappingParent", ":name") }}';
                                                url = url.replace(':name', selectedType);
                                                if (selectedType) {
                                                    $.ajax({
                                                        url: url,
                                                        type: "GET",
                                                        dataType: "json",
                                                        success: function(data) {
                                                            $('select[id="parentPoint"]').empty();
                                                            $('select[id="order_no"]').empty();

                                                            $('select[id="parentPoint"]').append(
                                                                '<option value="" selected>-- Select Parent --</option>'
                                                            );
                                                            $('select[id="order_no"]').append(
                                                                '<option value="" disabled selected>-- Select Order Number --</option>'
                                                            );
            
                                                            $.each(data, function(div, value) {
                                                                $('select[id="parentPoint"]').append(
                                                                    '<option value="' + value.id + '">' + value.parent_point_checklist + '</option>');
                                                            });
                                                        }
                                                    });
                                                } else {
                                                    $('select[id="parentPoint"]').empty();
                                                    $('select[id="order_no"]').empty();
                                                }
                                            });

                                            // Parent Change
                                            $(document).ready(function() {    
                                                $('#parentPoint').change(function() {
                                                    var parentPoint = $(this).val();
                                                    var typeChecklist = $('#type_checklist').val(); 
                                                    var url = '{{ route("mappingOrderNoChecklist", [":parentPoint", ":type"]) }}';
                                                    url = url.replace(':parentPoint', parentPoint).replace(':type', typeChecklist);
                                                    //alert(url);
                                                    if(typeChecklist) {
                                                        $.ajax({
                                                            url: url,
                                                            type: 'GET',
                                                            dataType: 'json',
                                                            success: function(data) {
                                                                //alert(data);
                                                                //console.log("AJAX Success Data:", gedungId); // Debugging
                                                                $('#order_no').empty();
                                                                $('#order_no').append('<option value="0">Change order to First</option>');
                                                                $('#order_no').append('<option value="99999">Change order to Last</option>');
                                                                $.each(data, function(key, value) {
                                                                    $('#order_no').append('<option value="' + value.order_no + '">' + value.order_no + ' - ' + value.sub_point_checklist + '</option>');
                                                                });
                                                            }
                                                        });
                                                    } else {
                                                        $('#order_no').empty();
                                                    }
                                                });
                                            })
                                        </script>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" id="btnUpdHeadCheck" class="btn btn-success waves-effect btn-label waves-light">
                                            <i class="mdi mdi-update label-icon"></i>Update
                                        </button>
                                    </div>
                                </form>
                                <script>
                                    document.getElementById('formUpdHeadCheck').addEventListener('submit', function(event) {
                                        if (!this.checkValidity()) {
                                            event.preventDefault(); return false;
                                        }
                                        var submitButton = this.querySelector('button[id="btnUpdHeadCheck"]');
                                        submitButton.disabled = true; submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                        return true;
                                    });
                                </script>
                            </div>
                        </div>
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
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div><span class="fw-bold">Parent Point Name :</span></div>
                                    <span>
                                        <span>{{ $parent->parent_point_checklist }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div><span class="fw-bold">Order Number Checklist :</span></div>
                                    <span>
                                        <span>{{ $checklist->order_no }}</span>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Checklist Detail</h5>
                            <a href="#" type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#editChecklist">
                                <i class="mdi mdi-file-edit label-icon"></i> Edit
                            </a>
                        </div>
                    </div>
                    {{-- Modal Edit --}}
                    <div class="modal fade" id="editChecklist" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Edit Checklist Detail</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('checklist.updateCheckDetail', encrypt($id)) }}" id="formUpdChecklist" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                        <div class="row">
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
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" id="btnUpdChecklist" class="btn btn-success waves-effect btn-label waves-light">
                                            <i class="mdi mdi-update label-icon"></i>Update
                                        </button>
                                    </div>
                                </form>
                                <script>
                                    document.getElementById('formUpdChecklist').addEventListener('submit', function(event) {
                                        if (!this.checkValidity()) {
                                            event.preventDefault(); return false;
                                        }
                                        var submitButton = this.querySelector('button[id="btnUpdChecklist"]');
                                        submitButton.disabled = true; submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                        return true;
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Mark Detail</h5>
                            <a href="#" type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#editMark">
                                <i class="mdi mdi-file-edit label-icon"></i> Edit
                            </a>
                        </div>
                    </div>
                    {{-- Modal Edit --}}
                    <div class="modal fade" id="editMark" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Edit Mark</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('checklist.updateMark', encrypt($id)) }}" id="formUpdMark" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                        <div class="row">
                                            <div class="col-lg-9 mb-3">
                                                @foreach($typeMark as $item)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="meta_name[]" value="{{ $item->id }}" id="checkbox_{{ $item->id }}" @if($mark->contains('meta_name', $item->name_value)) checked @endif>
                                                        <label class="form-check-label" for="checkbox_{{ $item->id }}">{{ $item->name_value }}</label>
                                                    </div>
                                                @endforeach
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const checkboxes = document.querySelectorAll('input[name="meta_name[]"]');
                                                        function updateRequiredState() {
                                                            const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                                                            checkboxes.forEach(checkbox => {
                                                                if (isChecked) {
                                                                    checkbox.removeAttribute('required');
                                                                } else {
                                                                    checkbox.setAttribute('required', 'required');
                                                                }
                                                            });
                                                        }
                                                        checkboxes.forEach(checkbox => {
                                                            checkbox.addEventListener('change', updateRequiredState);
                                                        });
                                                        updateRequiredState();
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" id="btnUpdMark" class="btn btn-success waves-effect btn-label waves-light">
                                            <i class="mdi mdi-update label-icon"></i>Update
                                        </button>
                                    </div>
                                </form>
                                <script>
                                    document.getElementById('formUpdMark').addEventListener('submit', function(event) {
                                        if (!this.checkValidity()) {
                                            event.preventDefault(); return false;
                                        }
                                        var submitButton = this.querySelector('button[id="btnUpdMark"]');
                                        submitButton.disabled = true; submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                        return true;
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <ul>
                                        @foreach($mark as $item)
                                            <li>{{ $item->meta_name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
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
                                    <div><span class="fw-bold">Upated At :</span></div>
                                    <span>
                                        <span>{{ $checklist->updated_at }}</span>
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