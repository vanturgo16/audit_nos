@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Mark Checklist</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Checklist</a></li>
                            <li class="breadcrumb-item active">Mark Checklist</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- @include('layouts.alert') --}}

        <div class="row">
            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>Type Checklist</b></td>
                            <td class="align-middle">: {{ $checklist->type_checklist }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Parent Point</b></td>
                            <td class="align-middle">: {{ $checklist->parent_point_checklist }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Child Point</b></td>
                            <td class="align-middle">: 
                                @if(empty($checklist->child_point_checklist))
                                    -
                                @else
                                    {{ $checklist->child_point_checklist }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Sub Point</b></td>
                            <td class="align-middle">: {{ $checklist->sub_point_checklist }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Mark</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Mark</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('checklist.markstore', encrypt($id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-lg-9 mb-3">
                                                    <label class="form-label">Meta Name</label><label style="color: darkred">*</label>
                                                    <div id="meta_name">
                                                        @foreach($type_mark as $item)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="meta_name[]" id="meta_name_{{ $item->id }}" value="{{ $item->id }}" @if($datas->contains('meta_name', $item->name_value)) checked @endif>
                                                                <label class="form-check-label" for="meta_name_{{ $item->id }}">{{ $item->name_value }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>


                                                </div>

                                                <script>
                                                    document.getElementById('meta_name').addEventListener('change', function () {
                                                        var checkboxes = this.querySelectorAll('input[type="checkbox"]:checked');
                                                        var selectedOptions = [];

                                                        checkboxes.forEach(function (checkbox) {
                                                            selectedOptions.push({
                                                                name_value: checkbox.value,
                                                                code_format: checkbox.getAttribute('data-code-format')
                                                            });
                                                        });

                                                        console.log(selectedOptions);
                                                    });
                                                </script>
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
                                    <th class="align-middle text-center">Result</th>
                                    <th class="align-middle text-center">Meta Name</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0;?> 
                                @foreach ($datas as $data)
                                <?php $no++ ;?>
                                    <tr>
                                        <td class="align-middle text-center">{{ $no }}</td>
                                        <td class="align-middle text-center"><b>{{ $data->result }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->meta_name }}</b></td>
                                        <td class="align-middle text-center">
                                            
                                            <a class="btn btn-sm btn-danger" href="{{ route('checklist.markdelete', encrypt($data->id)) }}"><span class="mdi mdi-delete-alert"></span> | Delete</a>
                                            
                                        </td>


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