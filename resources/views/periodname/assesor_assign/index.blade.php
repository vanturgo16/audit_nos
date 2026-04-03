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
            columns: [
                // Number
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-center align-top',
                    render: (data, type, row, meta) =>
                        meta.row + meta.settings._iDisplayStart + 1
                },
                // Dealer
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
                // Assessors
                {
                    data: 'assessors',
                    name: 'assessors',
                    className: 'align-top',
                    orderable: false,
                    render: function (data, type, row) {
                        if (!data || data.length === 0) return '-';
                        const maxVisible = 2;
                        let html = data
                            .slice(0, maxVisible)
                            .map(v => `<span class="badge bg-primary me-1 mb-1">${v}</span>`)
                            .join('');
                        if (data.length > maxVisible) {
                            const encoded = encodeURIComponent(JSON.stringify(data));
                            html += `
                                <span class="badge bg-secondary ms-1 mb-1 cursor-pointer show-assessor-modal"
                                    data-dealer="${row.dealer_name}"
                                    data-assessors="${encoded}">
                                    +${data.length - maxVisible} more
                                </span>
                            `;
                        }
                        return html;
                    }
                },
                // Status
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center align-top',
                    render: data =>
                        data === 0
                            ? '<span class="badge bg-secondary-subtle text-dark">Initiate</span>'
                            : '<span class="badge bg-warning text-white">Has starting</span>'
                },
                // Last updated
                {
                    data: 'last_updated_by',
                    name: 'last_updated_by',
                    className: 'align-top',
                    render: data => data ?? '-'
                },
                // Action
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top'
                },
                // Hidden type for search
                {
                    data: 'type',
                    name: 'type',
                    visible: false
                }
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

<!-- Modal Assessor Detail -->
<div class="modal fade" id="assessorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assessor List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="assessorModalBody"></div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.show-assessor-modal', function () {
    const dealer = $(this).data('dealer');
    const assessors = JSON.parse(decodeURIComponent($(this).data('assessors')));

    let content = `
        <div class="mb-2 fw-semibold">${dealer}</div>
        <div>
            ${assessors.map(v =>
                `<span class="badge bg-primary me-1 mb-1">${v}</span>`
            ).join('')}
        </div>
    `;

    $('#assessorModalBody').html(content);
    $('#assessorModal').modal('show');
});
</script>

@endsection