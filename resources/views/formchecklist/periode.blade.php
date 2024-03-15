@extends('layouts.master')
@section('konten')


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Form Checklist Jaringan</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Form Checklist Jaringan</li>
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
                        <!-- <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Jaringan</button> -->
                    </div>
                    <div class="card-body">

                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Period Checklist</th>
                                    <th class="align-middle text-center">Date</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Active</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0;?> 
                                @foreach ($datas as $data)
                                <?php $no++ ;?>
                                    <tr>
                                        <td class="align-middle text-center">{{ $no }}</td>
                                        <td class="align-middle text-center"><b>{{ $data->period }}</b></td>
                                        <td class="align-middle text-center">{{ Carbon\Carbon::parse($data->start_date)->format('d-m-Y') }}</td>
                                        <td class="align-middle text-center">
                                        @if($data->status == 1)
                                            <span class="badge bg-success text-white">Active</span>
                                        @elseif($data->status == 2)
                                            <span class="badge bg-success text-white">Active</span>
                                        @elseif($data->status == 3)
                                            <span class="badge bg-success text-white">Active</span> <span class="badge bg-warning text-white">Reviewed</span>
                                        @elseif($data->status == 4)
                                            <span class="badge bg-danger text-white">Closed Approved</span>
                                        @elseif($data->status == 5)
                                            <span class="badge bg-success text-white">Active</span> <span class="badge bg-danger text-white">Rejected</span>
                                        @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($data->is_active == 1)
                                                <span class="badge bg-success text-white">Active</span>
                                            @else
                                                <span class="badge bg-danger text-white">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('formchecklist.typechecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-primary"
                                                    aria-expanded="false">
                                                    <i class="mdi mdi-check-underline-circle"></i> Checklist 
                                                </a>
                                            </div>
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


@endsection