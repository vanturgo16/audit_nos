@extends('layouts.master')
@section('konten')


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Tabel Type Checklist</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Tabel Type Checklist</li>
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
                                    <th class="align-middle text-center">Type Checklist</th>
                                    <th class="align-middle text-center">Total Checklist</th>
                                    <th class="align-middle text-center">Checklist Remain</th>
                                    <th class="align-middle text-center">Start Date</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0;?> 
                                @foreach ($datas as $data)
                                <?php $no++ ;?>
                                    <tr>
                                        <td class="align-middle text-center">{{ $no }}</td>
                                        <td class="align-middle text-center">{{ $data->type_checklist }}</td>
                                        <td class="align-middle text-center">{{ $data->total_checklist - $data->checklist_remaining}} of {{ $data->total_checklist}}</td>
                                        <td class="align-middle text-center">{{ $data->checklist_remaining}}</td>
                                        <td class="align-middle text-center">
                                            @if($data->start_date == null)
                                                <span class="badge bg-secondary text-white">Not Started</span>
                                            @else
                                                {{ Carbon\Carbon::parse($data->start_date)->format('d-m-Y') }}
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($data->status == "")
                                                <span class="badge bg-secondary text-white">Not Started</span>
                                            @elseif($data->status == 0)
                                                <span class="badge bg-warning text-white">Not Complete</span>
                                            @elseif($data->status == 1)
                                                <span class="badge bg-info text-white">Reviewed</span>
                                            @elseif($data->status == 2)
                                                <span class="badge bg-danger text-white">Not Approve</span>
                                            @elseif($data->status == 3)
                                                <span class="badge bg-success text-white">Approve</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
                                                
                                                    @if($data->status == "")
                                                        <li><button class="dropdown-item drpdwn" data-bs-toggle="modal" data-bs-target="#start{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Start</button></li>
                                                    @elseif($data->status == 0 && $data->checklist_remaining != 0)
                                                        <li><a class="dropdown-item drpdwn" href="{{ route('formchecklist.checklistform', encrypt($data->id)) }}"><span class="mdi mdi-check-underline-circle"></span> | Update</a></li>
                                                    @elseif($data->status == 0)
                                                        <li><a class="dropdown-item drpdwn" href="#"><span class="mdi mdi-check-underline-circle"></span> | Update</a></li>
                                                        <li><a class="dropdown-item drpdwn" href="#"><span class="mdi mdi-check-underline-circle"></span> | Submit</a></li>
                                                    @elseif($data->status == 1)
                                                        <li><a class="dropdown-item drpdwn" href="#"><span class="mdi mdi-check-underline-circle"></span> | Detail</a></li>
                                                    @elseif($data->status == 2 && $data->checklist_remaining != 0)
                                                        <li><a class="dropdown-item drpdwn" href="#"><span class="mdi mdi-check-underline-circle"></span> | Update</a></li>
                                                    @elseif($data->status == 2)
                                                        <li><a class="dropdown-item drpdwn" href="#"><span class="mdi mdi-check-underline-circle"></span> | Update</a></li>
                                                        <li><a class="dropdown-item drpdwn" href="#"><span class="mdi mdi-check-underline-circle"></span> | Submit</a></li>
                                                    @elseif($data->status == 3)
                                                        <li><a class="dropdown-item drpdwn" href="#"><span class="mdi mdi-check-underline-circle"></span> | Detail</a></li>
                                                    @else
                                                        <li><a class="dropdown-item drpdwn" href="#"><span class="mdi mdi-check-underline-circle"></span> | Detail</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- Modal Info --}}
                                    <div class="modal fade" id="start{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Start</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <p class="text-center"> 
                                                            Are You Sure To Start This Checklist?
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a type="button" href="{{ route('formchecklist.start', encrypt($data->id)) }}" class="btn btn-primary">Yes</a>
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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