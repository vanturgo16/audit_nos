<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
        {{-- INFO --}}
        <li>
            <a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}">
                <i class="mdi mdi-information-outline me-1"></i>Info
            </a>
        </li>
        {{-- LIST ASSIGN --}}
        <li>
            <a class="dropdown-item drpdwn" href="{{ route('periodname.indexAssesorAssign', encrypt($data->id)) }}">
                <i class="mdi mdi-account-multiple-outline me-1"></i>Assign Assessor
            </a>
        </li>

        @if(in_array(Auth::user()->role, ['Super Admin', 'Admin', 'PIC Dealers']))
            <li><hr class="dropdown-divider"></li>
            {{-- EDIT --}}
            @if ($data->status == 0)
                {{-- Editable --}}
                <li>
                    <a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}">
                        <i class="mdi mdi-pencil-outline me-1"></i>Edit
                    </a>
                </li>
            @else
                {{-- Locked --}}
                <li>
                    <a class="dropdown-item text-muted disabled" href="#" title="This data is already used and cannot be edited">
                        <i class="mdi mdi-lock-outline me-1"></i>Locked (Used)
                    </a>
                </li>
            @endif
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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Period Name :</span></div>
                                <span>
                                    <span>{{ $data->period_name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
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

    @if(in_array(Auth::user()->role, ['Super Admin', 'Admin', 'PIC Dealers']))
        {{-- Modal Update --}}
        @if ($data->status == 0)
            <div class="modal fade" id="update{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-top" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="formLoad" action="{{ route('periodname.update', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <input class="form-control" name="category" type="hidden" value="Period Name">
                                    <input class="form-control" name="code_format" type="hidden" value="PN">
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Period Name</label><label style="color: darkred">*</label>
                                        <input class="form-control" name="period_name" type="text" value="{{ $data->period_name }}" placeholder="Input Name Period.." required>
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

            <script src="{{ asset('assets/js/formLoad.js') }}"></script>
        @endif
    @endif
</div>