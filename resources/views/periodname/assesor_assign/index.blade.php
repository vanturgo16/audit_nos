@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('periodname.index') }}">List Type Jaringan</a></li>
                            <li class="breadcrumb-item active">List Assign Assesor (Period: <b>{{ $periodName }}</b>)</li>
                        </ol>
                    </div>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <a id="backButton" type="button" href="{{ route('periodname.index')}}"
                                class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                                <i class="mdi mdi-arrow-left-circle label-icon"></i>
                                Back
                            </a>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped dt-responsive w-100" id="ssTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Dealer Name</th>
                                    <th class="align-middle text-center">Assesor</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Last Updated By</th>
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
            ajax: '{!! route('periodname.indexAssesorAssign', encrypt($id)) !!}',
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: null,
                    name: 'dealer',
                    className: 'align-top',
                    render: function (data, type, row) {
                        return `
                            <strong>${row.type}</strong><br>
                            <span>${row.dealer_name}</span>
                        `;
                    },
                },
                {
                    data: 'assessors',
                    name: 'assessors',
                    className: 'align-top',
                    render: function(data, type, row) {
                        if (!data || data.length === 0) return '-';

                        let maxVisible = 2;
                        let visible = data.slice(0, maxVisible).map(email => `<span class="badge bg-primary me-1">${email}</span>`).join(' ');
                        let remaining = data.length - maxVisible;
                        if (remaining > 0) {
                            let remainingEmails = data.slice(maxVisible).join(', ');
                            visible += `<span class="badge bg-secondary" data-bs-toggle="tooltip" title="${remainingEmails}">+${remaining} more</span>`;
                        }
                        return visible;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'align-top text-center',
                    render: function (data) {
                        if (data === 0) {
                            return '<span class="badge bg-secondary-subtle text-dark">Initiate</span>';
                        }
                        return '<span class="badge bg-warning text-white">Has starting</span>';
                    }
                },
                {
                    data: 'last_updated_by',
                    name: 'last_updated_by',
                    className: 'align-top',
                    render: function (data) {
                        return data ?? '-';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top',
                },
            ],
        });

        $('#ssTable').on('draw.dt', function() {
            $('.select2').each(function() {
                if (!$(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2({
                        dropdownParent: $(this).closest('.modal'),
                        placeholder: "Select Assessors",
                        width: '100%'
                    });
                }
            });
        });

    });
</script>

@endsection