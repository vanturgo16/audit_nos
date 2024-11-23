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
                            <li class="breadcrumb-item"><a href="{{ route('auditor.periodList') }}">List Period</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('auditor.periodDetail', encrypt($period->id)) }}">{{ $period->period }}</a></li>
                            <li class="breadcrumb-item active">Detail {{ $typeCheck }}</li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('auditor.periodDetail', encrypt($period->id)) }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @php
                function getBadge($value) {
                    if (!$value) return '-';
                    $badgeStyle = match ($value) {
                        'Bronze' => 'background-color: #cd7f32; color: white;',
                        'Silver' => 'background-color: #c0c0c0; color: black;',
                        'Gold' => 'background-color: #ffd700; color: black;',
                        'Platinum' => 'background: linear-gradient(135deg, #e5e4e2 0%, #f2f2f2 100%); color: black; text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.6); border: 1px solid #dcdcdc; box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.3);', // Shiny Platinum effect
                        default => 'background-color: #f8f9fa; color: black;',
                    };
                    return "<span class='badge' style='$badgeStyle'>$value</span>";
                }
            @endphp
            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>% Result</b></td>
                            <td class="align-middle">: {{ $chekJar->result_percentage }}%</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Result Audit</b></td>
                            <td class="align-middle">: {!! getBadge($chekJar->audit_result) !!}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Mandatory Item</b></td>
                            <td class="align-middle">: {!! getBadge($chekJar->mandatory_item) !!}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>RESULT FINAL</b></td>
                            <td class="align-middle">: {!! getBadge($chekJar->result_final) !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>Last Note Assessor</b></td>
                            <td class="align-middle">{{ $chekJar->last_reason_assessor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Last Note PIC NOS MD</b></td>
                            <td class="align-middle">{{ $chekJar->last_reason_pic ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
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
            ajax: '{!! route('auditor.detailChecklist', encrypt($id)) !!}',
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