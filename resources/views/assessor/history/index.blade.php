@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">History Log Decision Period ( {{$period->period}} )</h4>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('assessor.typechecklist', encrypt($period->id)) }}"
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
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>Period Name</b></td>
                            <td class="align-middle">: {{ $period->period }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Date</b></td>
                            <td class="align-middle">: {{ Carbon\Carbon::parse($period->start_date)->format('d-m-Y') }} <b> Until </b>{{ Carbon\Carbon::parse($period->end_date)->format('d-m-Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive w-100" id="server-side-table" style="font-size: small">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Finish Date</th>
                                    <th class="align-middle text-center">Note</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Info --}}
        @foreach($datas as $data)
        <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered dt-responsive w-100" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">No</th>
                                            <th class="align-middle text-center">Type Checklist</th>
                                            <th class="align-middle text-center">Submit Date</th>
                                            <th class="align-middle text-center">Decision</th>
                                            <th class="align-middle text-center">Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0;?> 
                                        @foreach ($data->submitlog as $log)
                                        <?php $no++ ;?>
                                            <tr>
                                                <td class="align-middle text-center">{{ $no }}</td>
                                                <td class="align-middle text-center"><b>{{ $log->type_checklist }}</b></td>
                                                <td class="align-middle text-center">{{ Carbon\Carbon::parse($log->date)->format('d-m-Y') }}</td>
                                                <td class="align-middle text-center">
                                                    @if($log->decision == 'Approved')
                                                        <span class="badge bg-success text-white">Approved</span>
                                                    @else
                                                        <span class="badge bg-danger text-white">Not Approved</span>
                                                    @endif      
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($log->reason == null)
                                                        <span class="badge bg-secondary text-white">Null</span>
                                                    @else
                                                        {{ $log->reason }}
                                                    @endif      
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

<script>
    $(function() {
        $('#server-side-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('assessor.history', encrypt($period->id)) !!}',
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
                    data: 'status',
                    orderable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.status == 4){
                            html = '<span class="badge bg-success text-white">Approved</span>';
                        } else if(row.status == 5){
                            html = '<span class="badge bg-warning text-white">Rejected</span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'date',
                    name: 'date',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle',
                    render: function(data, type, row) {
                        var html
                        var startDate = new Date(row.date);
                        startDate = startDate.toLocaleDateString('es-CL').replace(/\//g, '-')
                        html = '('+startDate+')<br><b>By : '+row.finish_by+'</b>';
                        return html;
                    },
                },
                {
                    orderable: true,
                    data: 'note',
                    name: 'note',
                    render: function(data, type, row) {
                        if(row.note == null){
                            return '<span class="badge bg-secondary text-white">Null</span>'
                        } else {
                            var truncatedData = data.length > 30 ? data.substr(0, 30) + '...' : data;
                            return truncatedData;
                        }
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