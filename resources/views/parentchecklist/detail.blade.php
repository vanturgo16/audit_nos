@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('parentchecklist.typechecklist') }}">List Type</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('parentchecklist.index', $parent->type_checklist) }}">List Parent Checklist (Type: {{ $parent->type_checklist }})</a></li>
                            <li class="breadcrumb-item active">Manage {{ \Illuminate\Support\Str::limit($parent->parent_point_checklist, 25, '...') }}</li>
                        </ol>
                    </div>
                    
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('parentchecklist.index', $parent->type_checklist) }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Parent Detail</h5>
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
                                <form action="{{ route('parentchecklist.updateParent', encrypt($id)) }}" id="formUpdHeadCheck" method="POST" enctype="multipart/form-data">
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
                                                <input type="text" name="add_parent" class="form-control" placeholder="Input New Parent" value="{{ $parent->parent_point_checklist }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <input type="hidden" name="order_current" value="{{ $parent->order_no }}">
                                                <input type="hidden" name="type_checklist_current" value="{{ $parent->type_checklist }}">
                                                <label class="form-label">Exchange Order Number</label>
                                                <select class="form-select js-example-basic-single" style="width: 100%" name="order_no" id="order_no">
                                                        <option value="0">Change order to First</option>
                                                        <option value="99999">Change order to Last</option>
                                                    @foreach($orders as $order)
                                                        <option value="{{ $order->order_no }}" @if($parent->order_no == $order->order_no) selected="selected" @endif> {{ $order->order_no . "-" . $order->parent_point_checklist }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6" id="guidechceklist">
                                                <label class="form-label">{{ $parent->path_guide_premises ? 'Update' : 'Upload' }} Guide Parent Checklist</label>
                                                @if($parent->path_guide_premises != null)
                                                    -> 
                                                    <a href="{{ url($parent->path_guide_premises) }}" target="_blank">
                                                        <u>Show File Before</u>
                                                    </a>
                                                @endif
                                                <input type="file" name="guide_parent" accept="image/png, image/jpeg, image/jpg" class="form-control" placeholder="Input Guide">
                                            </div>
                                        </div>
                                        <script>
                                            var type = '{{ $parent->type_checklist }}';
                                            var path = '{{ $parent->path_guide_premises }}';
                                            var typeChecklistPerCheck = @json($typeChecklistPerCheck);

                                            function updateGuideChecklist(typeChecklist) {
                                                if (!typeChecklistPerCheck.includes(typeChecklist)) {
                                                    $('#guidechceklist').show();
                                                    if (path) {
                                                        $('input[name="guide_parent"]').attr("required", false);
                                                    } else {
                                                        $('input[name="guide_parent"]').attr("required", true);
                                                    }
                                                } else {
                                                    $('#guidechceklist').hide();
                                                    $('input[name="guide_parent"]').attr("required", false);
                                                }
                                            }

                                            // Initial check
                                            updateGuideChecklist(type);

                                            // Handle change event
                                            $('select[name="type_checklist"]').on('change', function () {
                                                var selectedType = $(this).val();
                                                updateGuideChecklist(selectedType);

                                                var url = '{{ route("mappingOrderNo", ":id") }}';
                                                url = url.replace(':id', selectedType);
                                                if(selectedType) {
                                                    $.ajax({
                                                        url: url,
                                                        type: 'GET',
                                                        dataType: 'json',
                                                        success: function(data) {
                                                            $('#order_no').empty();
                                                            $('#order_no').append('<option value="0">Change order to First</option>');
                                                            $('#order_no').append('<option value="99999">Change order to Last</option>');
                                                            $.each(data, function(key, value) {
                                                                $('#order_no').append('<option value="' + value.order_no + '">' + value.order_no + ' - ' + value.parent_point_checklist + '</option>');
                                                            });
                                                        }
                                                    });
                                                } else {
                                                    $('#order_no').empty();
                                                }
                                            });
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
                                    <div><span class="fw-bold">Order Number Parent Checklist :</span></div>
                                    <span>
                                        <span>{{ $parent->order_no }}</span>
                                    </span>
                                </div>
                            </div>
                            @if(!in_array($parent->type_checklist, $typeChecklistPerCheck))
                                @if($parent->path_guide_premises != null)
                                    <div class="col-lg-6">
                                        <label class="form-label">File Guide Parent Checklist :</label>
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
                            @endif
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
                                        <span>{{ $parent->created_at }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <div class="form-group">
                                    <div><span class="fw-bold">Upated At :</span></div>
                                    <span>
                                        <span>{{ $parent->updated_at }}</span>
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