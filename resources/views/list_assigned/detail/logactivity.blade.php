@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('listassigned.periodList') }}">List Assigned Checklist</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('listassigned.periodDetail', encrypt($periodInfo->id)) }}">Detail {{ $periodInfo->period }}</a></li>
                            <li class="breadcrumb-item active">Log Activity</li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('listassigned.periodDetail', encrypt($periodInfo->id)) }}" class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Action By</th>
                                    <th class="align-middle text-center">Note</th>
                                </tr>
                            </thead>
                            @php
                                $statusLabels = [
                                    0 => '<span class="badge bg-secondary text-white"><i class="mdi mdi-play-box-edit-outline label-icon"></i> Initiate</span>',
                                    1 => '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Assigned - Checklist Process</span>',
                                    2 => '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Revision - Checklist Process</span>',
                                    3 => '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review Assessor</span>',
                                    4 => '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review PIC MD</span>',
                                    5 => '<span class="badge bg-success text-white"><i class="mdi mdi-check-all label-icon"></i> Approved - Done</span>',
                                    8 => '<span class="badge bg-warning text-white"><i class="mdi mdi-timer-alert label-icon"></i> Expired</span>',
                                    9 => '<span class="badge bg-primary text-white"><i class="mdi mdi-timer-play label-icon"></i> Extend Period</span>',
                                    'default' => '<span class="badge bg-secondary text-white">Null</span>',
                                ];
                            @endphp
                            <tbody>
                                @foreach($datas as $item)
                                <tr>
                                    <td class="align-top text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        {!! $statusLabels[$item->status] ?? $statusLabels['default'] !!}
                                    </td>
                                    <td>
                                        {{ $item->activity_by }} 
                                        <br> <b>At.</b> {{ $item->created_at }}
                                    </td>
                                    <td>
                                        {!! $item->note !!}
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

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $(".table-responsive").DataTable({
            responsive: true,
        });
    });
</script>

@endsection