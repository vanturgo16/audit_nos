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
                            <li class="breadcrumb-item active">List Checklist</li>
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
                                                <div class="col-lg-12">
                                                    <label class="form-label">Type Checklist</label><label style="color: darkred">*</label>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <select class="form-select js-example-basic-single" name="type_checklist" required>
                                                        <option value="" selected>-- Select Type --</option>
                                                        <option disabled>──────────</option>
                                                        @foreach($type_checklist as $item)
                                                            <option value="{{ $item->name_value }}" {{ old('name_value') == $item->name_value ? 'selected' : '' }}> {{ $item->name_value }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Point</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" name="parent_point_checklist" id="parentPoint" required>
                                                        <option value="" disabled selected>-- Select Parent --</option>
                                                    </select>
                                                </div>
                                                <script>
                                                    // getParentList
                                                    $('select[name="type_checklist"]').on('change', function() {
                                                        var typeChecklist = $(this).find('option:selected').val();
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
                                                                        '<option value="" disabled selected>-- Select Parent --</option>'+
                                                                        '<option disabled>──────────</option>'
                                                                    );

                                                                    $.each(data, function(div, value) {
                                                                        $('select[id="parentPoint"]').append(
                                                                            '<option value="' + value.id + '">' + value.parent_point_checklist + '</option>');
                                                                    });
                                                                    $('select[id="parentPoint"]').append(
                                                                        '<option disabled>──────────</option>' +
                                                                        '<option class="font-weight-bold" value="AddParent">Add New Parent</option>'
                                                                    );
                                                                }
                                                            });
                                                        } else {
                                                            $('select[id="parentPoint"]').empty();
                                                        }
                                                    });
                                                </script>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label d-block">Child Point ?<label style="color: darkred">*</label></label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="q_child_point" id="q_child_point1" value="0" checked>
                                                        <label class="form-check-label" for="q_child_point1">No</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="q_child_point" id="q_child_point2" value="1">
                                                        <label class="form-check-label" for="q_child_point2">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-3" id="newParent">
                                                    <label class="form-label">New Parent Point</label><label style="color: darkred">*</label>
                                                    <input type="text" name="add_parent" class="form-control" placeholder="Input New Parent">
                                                </div>
                                                <div class="col-lg-6 mb-3" id="newTumbnail">
                                                    <label class="form-label">Tumbnail</label><label style="color: darkred">*</label>
                                                    <input type="file" name="thumbnail" accept="image/png, image/jpeg, image/jpg" class="form-control" placeholder="Input Tumbnail">
                                                    <div id="warningTumb" style="color: red; display: none;">File size exceeds the maximum limit (3 MB). Please choose another file.</div>
                                                </div>
                                                <script>
                                                    document.getElementById('newTumbnail').addEventListener('change', function () {
                                                        var input = this.querySelector('input[type="file"]');
                                                        var maxSize = 3 * 1024 * 1024;
                                                        var errorDiv = document.getElementById('warningTumb');

                                                        if (input.files.length > 0) {
                                                            var fileSize = input.files[0].size;

                                                            if (fileSize > maxSize) {
                                                                errorDiv.style.display = 'block';
                                                                input.value = ''; // Reset input file
                                                            } else {
                                                                errorDiv.style.display = 'none';
                                                            }
                                                        }
                                                    });
                                                </script>
                                                <script>
                                                    //option select for new parent
                                                    $(document).ready(function () {
                                                        $('#newParent').hide();
                                                        $('#newTumbnail').hide();
                                                        $('#parentPoint').change(function () {
                                                            if ($(this).val() === 'AddParent') {
                                                                $('#newParent').show();
                                                                $('#newTumbnail').show();
                                                                $('input[name="add_parent"]').attr("required", true);
                                                                $('input[name="thumbnail"]').attr("required", true);
                                                            } else {
                                                                $('#newParent').hide();
                                                                $('#newTumbnail').hide();
                                                                $('input[name="add_parent"]').attr("required", false);
                                                                $('input[name="thumbnail"]').attr("required", false);
                                                            }
                                                        });
                                                    });
                                                </script>
                                                <div class="col-lg-6 mb-3" id="addchildPoint">
                                                    <label class="form-label">Child Point</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="child_checklist" type="text" value="" placeholder="Input Child Point..">
                                                </div>
                                                <script>
                                                    //option show hide child point
                                                    $(document).ready(function () {
                                                        $('#addchildPoint').hide();
                                                        $('input[name="q_child_point"]').change(function () {
                                                            if ($(this).val() === '1') {
                                                                $('#addchildPoint').show();
                                                                $('input[name="child_checklist"]').attr("required", true);
                                                            } else {
                                                                $('#addchildPoint').hide();
                                                                $('input[name="child_checklist"]').attr("required", false);
                                                                $('input[name="child_checklist"]').val("");
                                                            }
                                                        });
                                                    });
                                                </script>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Sub Point</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="sub_point_checklist" type="text" value="" placeholder="Input Sub Point.." required>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Indikator</label><label style="color: darkred">*</label>
                                                    <textarea name="indikator" id="indikator" required></textarea>
                                                    <script>
                                                        CKEDITOR.replace( 'indikator', {
                                                            toolbar: [
                                                                { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo' ] },
                                                                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace' ] },
                                                                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold' , 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ]},
                                                                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-'] },
                                                                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                                                                { name: 'others', items: [ '-' ] },
                                                            ]
                                                        });
                                                    </script>
                                                </div>
                                                
                                                <div class="col-12">
                                                    <div class="card p-2">
                                                        <div class="card-header p-1">
                                                            <div class="text-center text-bold">
                                                                Mandatory
                                                            </div>
                                                        </div>
                                                        <div class="row p-2">
                                                            <div class="col-lg-4 mb-3">
                                                                <label class="form-label d-block">Mandatory Silver<label style="color: darkred">*</label></label>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver1" value="0" required>
                                                                    <label class="form-check-label" for="mandatory_silver1">No</label>
                                                                </div>
            
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver2" value="1">
                                                                    <label class="form-check-label" for="mandatory_silver2">Yes</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 mb-3">
                                                                <label class="form-label d-block">Mandatory Gold<label style="color: darkred">*</label></label>
                                                                
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_gold" id="mandatory_gold1" value="0" required>
                                                                    <label class="form-check-label" for="mandatory_gold1">No</label>
                                                                </div>
            
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_gold" id="mandatory_gold2" value="1">
                                                                    <label class="form-check-label" for="mandatory_gold2">Yes</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 mb-3">
                                                                <label class="form-label d-block">Mandatory Platinum<label style="color: darkred">*</label></label>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_platinum" id="mandatory_platinum1" value="0" required>
                                                                    <label class="form-check-label" for="mandatory_platinum1">No</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_platinum" id="mandatory_platinum2" value="1">
                                                                    <label class="form-check-label" for="mandatory_platinum2">Yes</label>
                                                                </div>
                                                            </div>
                                                        </div>
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
                        <table class="table table-bordered dt-responsive nowrap w-100" id="server-side-table">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Type Checklist</th>
                                    <th class="align-middle text-center">Parent Point</th>
                                    <th class="align-middle text-center">Child Point</th>
                                    <th class="align-middle text-center">Sub Point</th>
                                    <th class="align-middle text-center">Mandatory</th>
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
            ajax: '{!! route('checklist.index') !!}',
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
                    data: 'type_checklist',
                    name: 'type_checklist',
                    orderable: true,
                    className: 'align-middle text-center text-bold'
                },
                {
                    data: 'parent_point_checklist',
                    name: 'parent_point_checklist',
                    orderable: true,
                    className: 'align-middle text-center'
                },
                {
                    data: 'child_point_checklist',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.child_point_checklist){
                            html = row.child_point_checklist;
                        } else {
                            html = '<span class="badge bg-secondary text-white">Not Set</span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'sub_point_checklist',
                    name: 'sub_point_checklist',
                    orderable: true,
                    className: 'align-middle text-center'
                },
                {
                    data: 'mandatory_silver',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html;
                        if(row.mandatory_silver == 0 && row.mandatory_gold == 0 && row.mandatory_platinum == 0){
                            html = '<span class="badge bg-secondary text-white">Not Set</span>';
                        } else {
                            html = '';
                        }
                        if(row.mandatory_silver == 1){
                            html += '<span class="badge bg-success text-white">S</span>';
                        }
                        if(row.mandatory_gold == 1){
                            html += '<span class="badge bg-success text-white">G</span>';
                        }
                        if(row.mandatory_platinum == 1){
                            html += '<span class="badge bg-success text-white">P</span>';
                        }
                        return html;
                    },
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