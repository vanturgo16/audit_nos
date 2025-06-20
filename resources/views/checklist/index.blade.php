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
                            <li class="breadcrumb-item active">List Checklist (Type: {{ $type }})</li>
                        </ol>
                    </div>
                    
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('checklist.typechecklist') }}"
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
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Checklist</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Checklist (Type : {{ $type }})</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('checklist.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="type_checklist" value="{{ $type }}">
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Parent Point</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="parent_point_checklist" required>
                                                        <option value="" selected>-- Select Parent --</option>
                                                        @foreach( $typeParent as $item)
                                                            <option value="{{ $item->id }}" {{ old('parent_point_checklist') == $item->parent_point_checklist ? 'selected' : '' }}> {{ $item->parent_point_checklist }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if(in_array($type, $typeChecklistPerCheck))
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Guide Checklist</label><label style="color: darkred">*</label>
                                                        <input type="file" name="guide_checklist" accept="image/png, image/jpeg, image/jpg" class="form-control" placeholder="Input Guide" required>
                                                    </div>
                                                    <div class="col-lg-6 mb-3"></div>
                                                @endif
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Child Point (Optional)</label>
                                                    <input class="form-control" name="child_checklist" type="text" value="" placeholder="Optional Input Child Point..">
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Sub Point</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="sub_point_checklist" type="text" value="" placeholder="Input Sub Point.." required>
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Indikator</label><label style="color: darkred">*</label>
                                                    <textarea id="ckeditor-classic" name="indikator"></textarea>
                                                </div>
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
                                                                    <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver1" value="0" required>
                                                                    <label class="form-check-label" for="mandatory_silver1">No</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="mandatory_silver" id="mandatory_silver2" value="1">
                                                                    <label class="form-check-label" for="mandatory_silver2">Yes</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4 mb-3">
                                                                <label class="form-label d-block">Mandatory Gold</label>
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
                                                                <label class="form-label d-block">Mandatory Platinum</label>
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
                                                <div class="col-lg-9 mb-3">
                                                    <label class="form-label">Mark</label><label style="color: darkred">*</label>
                                                    @foreach($typeMark as $item)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="meta_name[]" value="{{ $item->id }}" id="checkbox_{{ $item->id }}">
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
                                            <button type="submit" id="submitButton" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
                                        </div>
                                    </form>
                                    <script>
                                        document.getElementById('formadd').addEventListener('submit', function(event) {
                                            if (!this.checkValidity()) {
                                                event.preventDefault(); return false;
                                            }
                                            var submitButton = this.querySelector('button[name="sb"]');
                                            submitButton.disabled = true; submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                            return true;
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped dt-responsive w-100" id="ssTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle text-center">Parent Point</th>
                                    <th class="align-middle text-center">Order No</th>
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
        $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('checklist.index', $type) !!}',
            columns: [
                {
                    data: 'parent_point_checklist',
                    name: 'parent_point_checklist',
                    orderable: true,
                    className: 'align-top text-bold'
                },
                {
                    data: 'order_no',
                    name: 'order_no',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'child_point_checklist',
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var html
                        if(row.child_point_checklist){
                            html = row.child_point_checklist;
                        } else {
                            html = '-';
                        }
                        return html;
                    },
                },
                {
                    data: 'sub_point_checklist',
                    name: 'sub_point_checklist',
                    orderable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        if (data) {
                            var words = data.split(' ');
                            var limitedText = words.length > 10 ? words.slice(0, 10).join(' ') + '...' : data;
                            return limitedText;
                        } else {
                            return '';
                        }
                    },
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
            order: [1, 'asc'],
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var lastParent = null;
                var rowspan = 0;

                api.column(0, { page: 'current' }).data().each(function(parent, i) {
                    if (lastParent === parent) {
                        rowspan++;
                        $(rows).eq(i).find('td:eq(0)').remove(); // Remove duplicate cells in the `parent_point_checklist` column
                    } else {
                        if (lastParent !== null) {
                            $(rows).eq(i - rowspan).find('td:eq(0)').attr('rowspan', rowspan); // Set rowspan for previous group
                        }
                        lastParent = parent;
                        rowspan = 1; // Reset rowspan for the new parent
                    }
                });

                // Apply rowspan for the last group if necessary
                if (lastParent !== null) {
                    $(rows).eq(api.column(0, { page: 'current' }).data().length - rowspan).find('td:eq(0)').attr('rowspan', rowspan);
                }
            }
        });
    });
</script>

@endsection