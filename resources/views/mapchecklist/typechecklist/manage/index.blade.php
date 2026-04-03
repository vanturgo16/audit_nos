@extends('layouts.master')

@section('konten')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between py-0 mb-3">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('mapchecklist.index') }}">List Mapping Type Jaringan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('mapchecklist.type', encrypt($type)) }}">List Type Checklist <b>({{ $type }})</b></a></li>
                            <li class="breadcrumb-item active">Manage Mapping <b>{{ $typecheck }}</b></li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('mapchecklist.type', encrypt($type)) }}"
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
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" 
                            data-bs-toggle="modal" data-bs-target="#addNew">
                            <i class="mdi mdi-plus-box label-icon"></i> Add New Mapping Checklist
                        </button>
                        <!-- Modal Add -->
                        <div class="modal fade" id="addNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Mapping Checklist in <b>({{ $type }})</b> type {{ $typecheck }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('mapchecklist.addChecklist', encrypt($type)) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Master Checklist</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single"
                                                            name="id_mst_checklist"
                                                            id="id_mst_checklist"
                                                            style="width: 100%"
                                                            required>
                                                        <option value="">--Select Checklist--</option>
                                                        @foreach($availableChecklists as $item)
                                                            <option value="{{ $item->id }}"
                                                                    data-id_parent_checklist="{{ $item->id_parent_checklist }}"
                                                                    data-indikator='@json($item->indikator)'>
                                                                {{ $item->type_checklist }} - {{ $item->parent_point_checklist }}
                                                                @if($item->child_point_checklist)
                                                                    - {{ $item->child_point_checklist }}
                                                                @endif
                                                                - {{ $item->sub_point_checklist }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="id_parent_checklist" id="id_parent_checklist">
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Indikator</label>
                                                    <div id="indikator"
                                                        class="form-control"
                                                        style="min-height:140px; background:#e9ecef; overflow:auto;">
                                                        Select Checklist
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                $(document).ready(function(){
                                                    // Initialize Select2
                                                    $('.js-example-basic-single').select2({ width: '100%' });
                                                    // Function to load indikator and parent checklist
                                                    function loadIndikator() {
                                                        let selectedOption = $('#id_mst_checklist').find(':selected');
                                                        // Get indikator JSON string
                                                        let indikatorAttr = selectedOption.attr('data-indikator');
                                                        if(!indikatorAttr){
                                                            $('#indikator').html('Select Checklist');
                                                        } else {
                                                            // Decode JSON string back to HTML
                                                            let indikatorHTML = JSON.parse(indikatorAttr);
                                                            $('#indikator').html(indikatorHTML);
                                                        }
                                                        // Fill hidden parent checklist
                                                        let parentId = selectedOption.attr('data-id_parent_checklist') || '';
                                                        $('#id_parent_checklist').val(parentId);
                                                    }
                                                    // On change (Select2 compatible)
                                                    $('#id_mst_checklist').on('change.select2 change', function(){
                                                        loadIndikator();
                                                    });
                                                    // Load indikator & parent checklist on page load (edit mode)
                                                    loadIndikator();
                                                });
                                            </script>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive w-100 small" id="ssTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Parent Point</th>
                                    <th class="align-middle text-center">Child Point</th>
                                    <th class="align-middle text-center">Sub Point</th>
                                    <th class="align-middle text-center">Indikator</th>
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
    var table = $('#ssTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: false, // ✅ disable left + icon
        ajax: '{!! route("mapchecklist.detail", ["type" => encrypt($type), "typecheck" => encrypt($typecheck)]) !!}',
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                searchable: false,
                className: 'align-top text-center fw-bold',
            },
            {
                data: 'parent_point_checklist',
                name: 'parent_point_checklist',
                orderable: true,
                searchable: true,
                className: 'align-top fw-bold',
            },
            {
                data: 'child_point_checklist',
                orderable: true,
                searchable: true,
                className: 'align-top',
                render: function(data, type, row) {
                    return row.child_point_checklist ? row.child_point_checklist : '-';
                },
            },
            {
                data: 'sub_point_checklist',
                name: 'sub_point_checklist',
                orderable: true,
                searchable: true,
                className: 'align-top',
                render: function(data, type, row) {
                    if (data) {
                        var words = data.split(' ');
                        var limitedText = words.length > 15 ? words.slice(0, 15).join(' ') + '...' : data;
                        return limitedText;
                    } else {
                        return '';
                    }
                },
            },
            {
                data: 'indikator',
                orderable: true,
                searchable: true,
                className: 'align-top',
                render: function(data, type, row) {
                    if (!data) return '-';
                    return '<div class="indikator-text" style="max-height:36px; overflow:hidden; white-space:normal; word-wrap:break-word;">'
                        + data + '</div>'
                        + '<a href="javascript:void(0)" class="indikator-toggle" style="display:none; margin-left:4px; color:#0d6efd; font-weight:bold;">&#x25BC;</a>';
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'align-top text-center',
            },
        ],
        drawCallback: function(settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var lastParent = null;
            var rowspan = 1;

            api.column(1, { page: 'current' }).data().each(function(parent, i) {
                if (lastParent === parent) {
                    rowspan++;
                    $(rows).eq(i).find('td:eq(1)').remove();
                } else {
                    if (lastParent !== null) {
                        $(rows).eq(i - rowspan).find('td:eq(1)').attr('rowspan', rowspan);
                    }
                    lastParent = parent;
                    rowspan = 1;
                }
            });
            if (lastParent !== null) {
                $(rows).eq(api.column(1, { page: 'current' }).data().length - rowspan).find('td:eq(1)').attr('rowspan', rowspan);
            }

            // Show expand icon only if text overflows vertically
            $('.indikator-text').each(function() {
                var $this = $(this);
                if ($this[0].scrollHeight > $this[0].clientHeight) {
                    $this.siblings('.indikator-toggle').show();
                } else {
                    $this.siblings('.indikator-toggle').hide();
                }
            });
        }
    });

    // Expand/Collapse indikator
    $('#ssTable').on('click', '.indikator-toggle', function() {
        var $toggle = $(this);
        var $textDiv = $toggle.siblings('.indikator-text');

        if ($textDiv.hasClass('expanded')) {
            $textDiv.removeClass('expanded').css('max-height', '36px');
            $toggle.html('&#x25BC;'); // down arrow
        } else {
            $textDiv.addClass('expanded').css('max-height', 'none');
            $toggle.html('&#x25B2;'); // up arrow
        }
    });
});
</script>

@endsection