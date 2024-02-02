@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Master Checklist</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Checklist</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Checklist</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Checklist</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('checklist.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <select class="form-select" name="type_checklist" required>
                                                        <option value="" selected>-- Select Type --</option>
                                                        <option disabled>──────────</option>
                                                        @foreach($type_checklist as $item)
                                                            <option value="{{ $item->name_value }}" {{ old('name_value') == $item->name_value ? 'selected' : '' }}> {{ $item->name_value }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Point</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="point_checklist" type="text" value="" placeholder="Input Point.." required>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Sub Point</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="sub_point_checklist" type="text" value="" placeholder="Input Sub Point.." required>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Indikator</label><label style="color: darkred">*</label>
                                                    <textarea class="form-control" rows="2" type="text" class="form-control" name="indikator" placeholder="(Input your Question)" value="{{ old('indikator') }}" required></textarea>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label d-block">Mandatory Silver<label style="color: darkred">*</label></label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver1" value="0">
                                                        <label class="form-check-label" for="mandatory_silver1">No</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver2" value="1">
                                                        <label class="form-check-label" for="mandatory_silver2">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label d-block">Mandatory Gold<label style="color: darkred">*</label></label>
                                                    
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="mandatory_gold" id="mandatory_gold1" value="0">
                                                        <label class="form-check-label" for="mandatory_gold1">No</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="mandatory_gold" id="mandatory_gold2" value="1">
                                                        <label class="form-check-label" for="mandatory_gold2">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label d-block">Mandatory Platinum<label style="color: darkred">*</label></label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="mandatory_platinum" id="mandatory_platinum1" value="0">
                                                        <label class="form-check-label" for="mandatory_platinum1">No</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="mandatory_platinum" id="mandatory_platinum2" value="1">
                                                        <label class="form-check-label" for="mandatory_platinum2">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label d-block">Upload File<label style="color: darkred">*</label></label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="upload_file" id="upload_file1" value="0">
                                                        <label class="form-check-label" for="upload_file1">No</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="upload_file" id="upload_file2" value="1">
                                                        <label class="form-check-label" for="upload_file2">Yes</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" id="submitButton" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
                                        </div>
                                    </form>
                                    <script>
                                        document.getElementById('formadd').addEventListener('submit', function(event) {
                                            if (!this.checkValidity()) {
                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                return false;
                                            }
                                            var submitButton = this.querySelector('button[name="sb"]');
                                            submitButton.disabled = true;
                                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                            return true; // Allow form submission
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Type Checklist</th>
                                    <th class="align-middle text-center">Point</th>
                                    <th class="align-middle text-center">Sub Point</th>
                                    <th class="align-middle text-center">Indikator</th>
                                    <th class="align-middle text-center">Mandatory</th>
                                    <th class="align-middle text-center">File Upload</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0;?> 
                                @foreach ($datas as $data)
                                <?php $no++ ;?>
                                    <tr>
                                        <td class="align-middle text-center">{{ $no }}</td>
                                        <td class="align-middle text-center"><b>{{ $data->type_checklist }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->point_checklist }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->sub_point_checklist }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->indikator }}</b></td>
                                        <td class="align-middle text-center">
                                            @if($data->mandatory_silver == 1)
                                                <span class="badge bg-success text-white">S</span>
                                            @endif
                                            @if($data->mandatory_gold == 1)
                                                <span class="badge bg-success text-white">G</span>
                                            @endif
                                            @if($data->mandatory_platinum == 1)
                                                <span class="badge bg-success text-white">P</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center"> 
                                            @if($data->upload_file == 1)
                                                <span class="badge bg-success text-white">Yes</span>
                                            @else
                                                <span class="badge bg-danger text-white">No</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
                                                    <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>
                                                    <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
                                                    <li><a class="dropdown-item drpdwn" href="{{ route('checklist.mark', encrypt($data->id)) }}"><span class="mdi mdi-file-eye"></span> | Mark</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        
                                        {{-- Modal Info --}}
                                        <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Info Checklist</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Point Name :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->point_checklist }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Created At :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->created_at }}</span>
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

                                        {{-- Modal Update --}}
                                        <div class="modal fade" id="update{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Checklist</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('checklist.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 mb-3">
                                                                <select class="form-select" name="type_checklist" required>
                                                                    <option value="" selected>-- Select Type --</option>
                                                                    <option disabled>──────────</option>
                                                                    @foreach($type_checklist as $item)
                                                                        <option value="{{ $item->name_value }}" @if($data->type_checklist == $item->name_value) selected="selected" @endif> {{ $item->name_value }} </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-6 mb-3">
                                                                <label class="form-label">Point</label><label style="color: darkred">*</label>
                                                                <input class="form-control" name="point_checklist" type="text" value="{{ $data->point_checklist }}" placeholder="Input Point.." required>
                                                            </div>
                                                            <div class="col-lg-6 mb-3">
                                                                <label class="form-label">Sub Point</label><label style="color: darkred">*</label>
                                                                <input class="form-control" name="sub_point_checklist" type="text" value="{{ $data->sub_point_checklist }}" placeholder="Input Sub Point.." required>
                                                            </div>
                                                            <div class="col-lg-12 mb-3">
                                                                <label class="form-label">Indikator</label><label style="color: darkred">*</label>
                                                                <textarea class="form-control" rows="2" type="text" class="form-control" name="indikator" placeholder="(Input your Question)" required>{{ $data->indikator }}</textarea>
                                                            </div>
                                                            <div class="col-lg-6 mb-3">
                                                                <label class="form-label d-block">Mandatory Silver<label style="color: darkred">*</label></label>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver1" value="0" @if($data->mandatory_silver == 0) checked @endif>
                                                                    <label class="form-check-label" for="mandatory_silver1">No</label>
                                                                </div>

                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver2" value="1" @if($data->mandatory_silver == 1) checked @endif>
                                                                    <label class="form-check-label" for="mandatory_silver2">Yes</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 mb-3">
                                                                <label class="form-label d-block">Mandatory Gold<label style="color: darkred">*</label></label>
                                                                
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_gold" id="mandatory_gold1" value="0" @if($data->mandatory_gold == 0) checked @endif>
                                                                    <label class="form-check-label" for="mandatory_gold1">No</label>
                                                                </div>

                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_gold" id="mandatory_gold2" value="1" @if($data->mandatory_gold == 1) checked @endif>
                                                                    <label class="form-check-label" for="mandatory_gold2">Yes</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 mb-3">
                                                                <label class="form-label d-block">Mandatory Platinum<label style="color: darkred">*</label></label>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_platinum" id="mandatory_platinum1" value="0"  @if($data->mandatory_platinum == 0) checked @endif>
                                                                    <label class="form-check-label" for="mandatory_platinum1">No</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_platinum" id="mandatory_platinum2" value="1"  @if($data->mandatory_platinum == 1) checked @endif>
                                                                    <label class="form-check-label" for="mandatory_platinum2">Yes</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 mb-3">
                                                                <label class="form-label d-block">Upload File<label style="color: darkred">*</label></label>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="upload_file" id="upload_file1" value="0" @if($data->upload_file == 0) checked @endif>
                                                                    <label class="form-check-label" for="upload_file1">No</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="upload_file" id="upload_file2" value="1" @if($data->upload_file == 1) checked @endif>
                                                                    <label class="form-check-label" for="upload_file2">Yes</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary waves-effect btn-label waves-light" id="sb-update{{ $data->id }}"><i class="mdi mdi-update label-icon"></i>Update</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        $(document).ready(function() {
                                                            let idList = "{{ $data->id }}";
                                                            $('#formedit' + idList).submit(function(e) {
                                                                if (!$('#formedit' + idList).valid()){
                                                                    e.preventDefault();
                                                                } else {
                                                                    $('#sb-update' + idList).attr("disabled", "disabled");
                                                                    $('#sb-update' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



@endsection