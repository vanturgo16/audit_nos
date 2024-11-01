@extends('layouts.master')

@section('konten')

<style>    
    /* Style Image Hover */
    .custom-image-container {
        position: relative;
        width: 100%;
        height: 7vh;
        overflow: hidden;
    }
    .custom-image-container:hover .custom-overlay {
        opacity: 1;
    }
    .custom-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .custom-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .custom-text {
        color: white;
        font-size: 10px;
        text-align: center;
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between py-0 mb-3">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('assessor.periodList') }}">List Period</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('assessor.periodDetail', encrypt($period->id)) }}">{{ $period->period }}</a></li>
                            <li class="breadcrumb-item active">Review {{ $typeCheck }}</li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('assessor.periodDetail', encrypt($period->id)) }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        {{-- Note --}}
        <table class="table nowrap w-100">
            <tbody>
                <tr>
                    <td class="align-top">
                        Note (Optional) :
                        <br>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editnote">
                            <span class="mdi mdi-pen"></span>
                        </button>
                    </td>
                    <td class="align-top">{{ $note }}</td>
                </tr>
            </tbody>
        </table>
        <div class="modal fade" id="editnote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('assessor.updateNoteChecklist', encrypt($id)) }}" method="post" enctype="multipart/form-data" id="updnotes">
                        @csrf
                        <div class="modal-body" style="max-height: 65vh; overflow-x:auto;">
                            <div class="row px-2">
                                <textarea class="form-control" rows="3" type="text" name="note" placeholder="(Note For This Type Checklist)">{{ $note }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="btnUpdNotes"><i class="fas fa-sync fa-fw" aria-hidden="true"></i> Update</button>
                        </div>
                    </form>
                    <script>
                        document.getElementById('updnotes').addEventListener('submit', function(event) {
                            if (!this.checkValidity()) {
                                event.preventDefault(); // Prevent form submission if it's not valid
                                return false;
                            }
                            var submitButton = this.querySelector('button[id="btnUpdNotes"]');
                            submitButton.disabled = true;
                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                            return true; // Allow form submission
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="server-side-table">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Parent Point</th>
                                    <th class="align-middle text-center">Child Point</th>
                                    <th class="align-middle text-center">Sub Point</th>
                                    <th class="align-middle text-center">Detail</th>
                                    <th class="align-middle text-center">Response</th>
                                    <th class="align-middle text-center">Photo</th>
                                    <th class="align-middle text-center">Decision</th>
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
        var table = $('#server-side-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('assessor.reviewChecklist', encrypt($id)) !!}',
            pageLength: 100,
            columns: [
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'parent_point_checklist',
                    name: 'parent_point_checklist',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'child_point_checklist',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return row.child_point_checklist
                            ? row.child_point_checklist
                            : '-';
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
                            var spc = row.sub_point_checklist;
                            var spc = spc.length > 35 ? spc.substr(0, 35) + '...' : spc;
                            return spc;
                        } else {
                            return '';
                        }
                    },
                },
                {
                    data: 'detail',
                    name: 'detail',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'response',
                    name: 'response',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'photo',
                    name: 'photo',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
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
                        $(rows).eq(i).find('td:eq(1)').remove(); // Remove duplicate cells in the `parent_point_checklist` column
                    } else {
                        if (lastParent !== null) {
                            $(rows).eq(i - rowspan).find('td:eq(1)').attr('rowspan', rowspan); // Set rowspan for previous group
                        }
                        lastParent = parent;
                        rowspan = 1;
                    }
                });

                // Apply rowspan for the last group
                if (lastParent !== null) {
                    $(rows).eq(api.column(1, { page: 'current' }).data().length - rowspan).find('td:eq(1)').attr('rowspan', rowspan);
                }
            }
        });
    });
</script>

@endsection