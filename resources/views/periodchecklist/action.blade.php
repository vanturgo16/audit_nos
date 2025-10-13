<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>

        {{-- Full Edit --}}
        @if($data->is_active == 1 && $data->status == 0)
            <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
        @endif

        {{-- Extend Period --}}
        @if($data->is_active == 0)
            <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#updateexpired{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Extend Period</a></li>
        @endif

        {{-- Detail --}}
        <li>
            <a class="dropdown-item drpdwn" href="{{ route('assignchecklist.index', encrypt($data->id)) }}">
                <span class="mdi mdi-check-underline-circle"></span> | 
                @if($data->is_active == 1 && $data->status == 0)
                    Assign Checklist
                @else
                    Detail Checklist
                @endif
            </a>
        </li>

        {{-- Delete --}}
        @if($data->is_active == 1 && $data->status == 0 && $data->decisionpic == '' && $data->notespic == '')
            <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#delete-period{{ $data->id }}"><span class="mdi mdi-delete"></span> | Delete Period</a></li>
        @endif
    </ul>
</div>

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Info --}}
    <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="form-group">
                                <div><span class="fw-bold">Status :</span></div>
                                <span>
                                    @php
                                        $statusLabels = [
                                            0 => '<span class="badge bg-secondary text-white"><i class="mdi mdi-play-box-edit-outline label-icon"></i> Initiate</span>',
                                            1 => '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Assigned - Checklist Process</span>',
                                            2 => '<span class="badge bg-info text-white"><i class="mdi mdi-sync label-icon"></i> Revision - Checklist Process</span>',
                                            3 => '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review Assessor</span>',
                                            4 => '<span class="badge bg-primary text-white"><i class="mdi mdi-message-draw label-icon"></i> Review PIC MD</span>',
                                            5 => '<span class="badge bg-success text-white"><i class="mdi mdi-check-all label-icon"></i> Approved - Done</span>',
                                            'default' => '<span class="badge bg-secondary text-white">Null</span>',
                                        ];
                                    @endphp
                                    @if($data->is_active == 1)
                                        {!! $statusLabels[$data->status] ?? $statusLabels['default'] !!}
                                    @else
                                        <span class="badge bg-warning text-white"><i class="mdi mdi-timer-alert-outline label-icon"></i> Expired</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Period Checklist :</span></div>
                                <span>
                                    <span>{{ $data->period }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Branch Name :</span></div>
                                <span>
                                    <span>{{ $data->dealer_name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Start Date :</span></div>
                                <span>
                                    <span>{{ $data->start_date }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">End Date :</span></div>
                                <span>
                                    <span>{{ $data->end_date }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Created At :</span></div>
                                <span>
                                    <span>{{ $data->created_at }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Update --}}
    @if($data->is_active == 1 && $data->status == 0)
        <div class="modal fade" id="update{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="formLoad" action="{{ route('periodchecklist.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Period Checklist</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="period" style="width: 100%" required>
                                        <option value="" selected>-- Select Period --</option>
                                        @foreach($period_name as $item)
                                            <option value="{{ $item->period_name }}" @if($data->period == $item->period_name) selected="selected" @endif>
                                                {{ $item->period_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label"><i>Jaringan</i> Name</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" name="id_branch" style="width: 100%" required>
                                        <option value="" selected>-- Select Jaringan --</option>
                                        @foreach($branchs as $item)
                                            <option value="{{ $item->id }}" @if($data->id_branch == $item->id) selected="selected" @endif>{{ $item->dealer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-6 mb-3">
                                    <label class="form-label">Start Date</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="start_date" type="date" value="{{ $data->start_date }}" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">End Date</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="end_date" type="date" value="{{ $data->end_date }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-update label-icon"></i>Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Update Expired --}}
    @if($data->is_active == 0)
        <div class="modal fade" id="updateexpired{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Extend Period</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="formLoad" action="{{ route('periodchecklist.updateexpired', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Period Checklist :</span></div>
                                        <span>
                                            <span>{{ $data->period }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Branch Name :</span></div>
                                        <span>
                                            <span>{{ $data->dealer_name }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Start Date :</span></div>
                                        <span>
                                            <span>{{ $data->start_date }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <div><span class="fw-bold">End Date :</span></div>
                                        <span>
                                            <span>{{ $data->end_date }}</span>
                                        </span>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Update End Date</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="end_date" type="date" value="{{ $data->end_date }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary waves-effect btn-label waves-light">
                                <i class="mdi mdi-update label-icon"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Delete Period --}}
    @if($data->is_active == 1 && $data->status == 0 && $data->decisionpic == '' && $data->notespic == '')
        <div class="modal fade" id="delete-period{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Delete Period</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="formLoad" action="{{ route('periodchecklist.delete', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="text-center">
                                Are You Sure to <b>Delete</b> This Period Checklist?
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                                <i class="mdi mdi-close-circle label-icon"></i>Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>