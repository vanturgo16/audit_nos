@extends('layouts.master')

@section('konten')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between py-0 mb-3">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $period->period }} - ({{ $period->dealer_name }})</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('assignchecklist.index', encrypt($period->id)) }}">Assign Checklists</a></li>
                            <li class="breadcrumb-item active">{{ $type }}</li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('assignchecklist.index', encrypt($period->id)) }}"
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
                    @if($period->is_active == 0)
                        <div class="card-header">
                            <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Assign Checklist</button>
                            {{-- Modal Add --}}
                            <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Add New Assign Checklist</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('assignchecklist.store', encrypt($period->id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 mb-2">
                                                        <label class="form-label">Master Checklist</label><label style="color: darkred">*</label>
                                                        <select class="form-select js-example-basic-single" name="id_mst_checklist" id="id_mst_checklist" style="width: 100%" required>
                                                            <option value="" selected>--Select Checklist--</option>
                                                            @foreach($checklists as $item)
                                                                <option value="{{ $item->id_checklist }}">
                                                                    {{ $item->type_checklist }} - {{ $item->parent_point_checklist }} 
                                                                    @if($item->child_point_checklist != null)
                                                                        - {{ $item->child_point_checklist }}
                                                                    @endif
                                                                    - {{ $item->sub_point_checklist }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <label class="form-label">Indikator</label>
                                                        <textarea class="form-control" id="indikator" rows="5" placeholder="Auto Fill.." readonly>Select Checklist</textarea>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Mandatory</label>
                                                        <div id="mandatory">
                                                            <span class="badge bg-secondary text-white">Select Checklist</span>
                                                            {{-- <span class="badge bg-success text-white">S</span>
                                                            <span class="badge bg-success text-white">G</span>
                                                            <span class="badge bg-success text-white">P</span> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">File Upload</label>
                                                        <div id="fileupload">
                                                            <span class="badge bg-secondary text-white">Select Checklist</span>
                                                            {{-- <span class="badge bg-success text-white">Yes</span> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
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

                                            $(document).ready(function(){
                                                $('#id_mst_checklist').change(function(){
                                                    var id = $(this).val();
                                                    if(id == ""){
                                                        $('#indikator').html('Select Checklist');
                                                        $('#mandatory').html('<span class="badge bg-secondary text-white">Select Checklist</span>');
                                                        $('#fileupload').html('<span class="badge bg-secondary text-white">Select Checklist</span>');
                                                    } else {
                                                        $.ajax({
                                                            url: '/searchchecklist/' + id,
                                                            type: 'GET',
                                                            success: function(data) {
                                                                var strippedHtml = $('<div>').html(data.indikator).text();
                                                                $('#indikator').html(strippedHtml);
                                                                console.log(data)
                                                                var html = ''
                                                                if(data.mandatory_silver == 1){
                                                                    html += '<span class="badge bg-success text-white">S</span>';
                                                                }
                                                                if(data.mandatory_gold == 1){
                                                                    html += '<span class="badge bg-success text-white">G</span>';
                                                                }
                                                                if(data.mandatory_platinum == 1){
                                                                    html += '<span class="badge bg-success text-white">P</span>';
                                                                }
                                                                $('#mandatory').html(html);
                                                                if(data.mandatory_silver != 1 && data.mandatory_gold != 1 && data.mandatory_platinum != 1){
                                                                    $('#mandatory').html('<span class="badge bg-secondary text-white">Null</span>');
                                                                }
                                                                if(data.upload_file == 1){
                                                                    $('#fileupload').html('<span class="badge bg-success text-white">Yes</span>');
                                                                } else {
                                                                    $('#fileupload').html('<span class="badge bg-danger text-white">No</span>');
                                                                }
                                                            }
                                                        });
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="server-side-table">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Type Checklist</th>
                                    <th class="align-middle text-center">Parent Point</th>
                                    <th class="align-middle text-center">Child Point</th>
                                    <th class="align-middle text-center">Sub Point</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(function() {
        $('#server-side-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('assignchecklist.type', ['id' => encrypt($period->id), 'type' => $type]) !!}',
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
                {
                    orderable: true,
                    searchable: true,
                    data: 'type_checklist',
                    name: 'type_checklist',
                    className: 'align-middle text-center',
                },
                {
                    orderable: true,
                    searchable: true,
                    data: 'parent_point_checklist',
                    name: 'parent_point_checklist',
                    className: 'align-middle text-center',
                },
                {
                    data: 'child_point_checklist',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.child_point_checklist == null){
                            html = '<span class="badge bg-secondary text-white">Null</span>';
                        } else {
                            html = row.child_point_checklist;
                        }
                        return html;
                    },
                },
                {
                    orderable: true,
                    searchable: true,
                    data: 'sub_point_checklist',
                    name: 'sub_point_checklist',
                    className: 'align-middle text-center',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
            ],
        });
    });

</script>
@endsection