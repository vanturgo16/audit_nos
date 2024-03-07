{{-- Modal Add --}}
<div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employee.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <select class="form-select js-example-basic-single" name="id_dealer" required>
                                <option value="" selected>-- Select Dealer --</option>
                                <option disabled>──────────</option>
                                @foreach( $dealer as $item)
                                    <option value="{{ $item->id }}" {{ old('dealer_name') == $item->dealer_name ? 'selected' : '' }}> {{ $item->dealer_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" name="id_dept" id="selectdepartment" required>
                                <option value="" selected>-- Select department --</option>
                                <option disabled>──────────</option>
                                @foreach( $department as $item)
                                    <option value="{{ $item->id }}" {{ old('department_name') == $item->department_name ? 'selected' : '' }}> {{ $item->department_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" name="id_position" id="selectPosition" required>
                                <option value="" selected>-- Select Position --</option>
                                <option disabled>──────────</option>
                                <!-- ini isi dari ajax -->
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Email</label><label style="color: darkred">*</label>
                            <input class="form-control" name="email" type="email" id="cek_mail" value="" placeholder="Input Email.." required>
                            <p id="emailWarning" style="color: darkred;"></p>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Employee Name</label><label style="color: darkred">*</label>
                            <input class="form-control" name="employee_name" type="text" value="" placeholder="Input Employee Name.." required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Employee NIK</label><label style="color: darkred">*</label>
                            <input class="form-control" name="employee_nik" type="text" value="" placeholder="Input Employee NIK.." required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Employee Telephone</label><label style="color: darkred">*</label>
                            <input class="form-control" name="employee_telephone" type="text" value="" placeholder="Input Employee Telephone.." required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Employee address</label><label style="color: darkred">*</label>
                            <textarea class="form-control" rows="3" type="text" class="form-control" name="employee_address" placeholder="(Input Employee Address, Ex. Street/Unit/Floor/No)" value="{{ old('employee_address') }}" required></textarea>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" name="province" id="province" class="form-control" required>
                                <option value="" selected>-- Select Province --</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province['nama'] }}"
                                    data-idProv="{{ $province['id'] }}">
                                    {{ $province['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" name="city" id="city" class="form-control" required>
                                <option value="" selected>- Select City -</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" name="district" id="district" class="form-control" required>
                                <option value="" selected>- Select District -</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" name="subdistrict" id="subdistrict" class="form-control" required>
                                <option value="" selected>- Select Subdistrict -</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <input name="zipcode" id="zipcode" type="text" class="form-control" placeholder="Input Postal Code" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="submitButton" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
                </div>
            </form>
            <script>
                document.getElementById('formadd').addEventListener('submit', function(event) {
                    if (!this.checkValidity()) {
                        event.preventDefault(); // Prevent form submission if it's not valid
                        return false;
                    }
                    var submitButton = this.querySelector('button[name="sb"]');
                    submitButton.disabled = true;
                    submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                    return true; // Allow form submission
                });
            </script>
        </div>
    </div>
</div>

@foreach ($datas as $data)
    {{-- Modal Info --}}
    <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Info employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Employee Name :</span></div>
                                <span>
                                    <span>{{ $data->employee_name }}</span>
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

    {{-- Modal Update --}}
    <div class="modal fade" id="update{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('employee.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <select class="form-select js-example-basic-single" name="id_dealer" required>
                                    <option value="" selected>-- Select Dealer --</option>
                                    <option disabled>──────────</option>
                                    @foreach( $dealer as $item)
                                        <option value="{{ $item->id }}" @if($data->id_dealer == $item->id) selected="selected" @endif> {{ $item->dealer_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <select class="form-select js-example-basic-single" name="id_dept" id="selecteditDepartment{{ $data->id }}" required>
                                    <option value="" selected>-- Select Department --</option>
                                    <option disabled>──────────</option>
                                    @foreach( $department as $item)
                                        <option value="{{ $item->id }}" @if($data->id_dept == $item->id) selected="selected" @endif> {{ $item->department_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <select class="form-select js-example-basic-single" name="id_position" id="selecteditPosition{{ $data->id }}" required>
                                    <option value="" selected>-- Select Position --</option>
                                    <option disabled>──────────</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Email</label><label style="color: darkred">*</label>
                                <input class="form-control" name="email" type="email" value="{{ $data->email }}" placeholder="Input Email.." required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Employee Name</label><label style="color: darkred">*</label>
                                <input class="form-control" name="employee_name" type="text" value="{{ $data->employee_name }}" placeholder="Input Employee Name.." required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Employee NIK</label><label style="color: darkred">*</label>
                                <input class="form-control" name="employee_nik" type="text" value="{{ $data->employee_nik }}" placeholder="Input Employee NIK.." required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Employee Telephone</label><label style="color: darkred">*</label>
                                <input class="form-control" name="employee_telephone" type="text" value="{{ $data->employee_telephone }}" placeholder="Input Employee Telephone.." required>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Employee address</label><label style="color: darkred">*</label>
                                    <textarea class="form-control" rows="3" type="text" class="form-control" name="employee_address" placeholder="(Input Employee Address, Ex. Street/Unit/Floor/No)" required>{{ $data->employee_address }}</textarea>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <select class="form-select js-example-basic-single" name="province" id="province{{ $data->id }}" class="form-control" required>
                                        <option value="" selected>-- Select Province --</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province['nama'] }}"
                                            data-idProv="{{ $province['id'] }}" @if($data->province == $province['nama']) selected="selected" @endif>
                                            {{ $province['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <select class="form-select js-example-basic-single" name="city" id="city{{ $data->id }}" class="form-control" required>
                                        <option value="" selected>- Select City -</option>
                                        <option value="{{ $data->city }}" selected>{{ $data->city }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <select class="form-select js-example-basic-single" name="district" id="district{{ $data->id }}" class="form-control" required>
                                        <option value="" selected>- Select District -</option>
                                        <option value="{{ $data->district }}" selected>{{ $data->district }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <select class="form-select js-example-basic-single" name="subdistrict" id="subdistrict{{ $data->id }}" class="form-control" required>
                                        <option value="" selected>- Select Subdistrict -</option>
                                        <option value="{{ $data->subdistrict }}" selected>{{ $data->subdistrict }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <input name="zipcode" id="zipcode{{ $data->id }}" type="text" class="form-control" placeholder="Input Postal Code" value="{{ $data->postal_code }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light" id="sb-update{{ $data->id }}"><i class="mdi mdi-update label-icon"></i>Update</button>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        let idList = "{{ $data->id }}";
                        $('#formedit' + idList).submit(function(e) {
                            if (!$('#formedit' + idList).valid()){
                                e.preventDefault();
                            } else {
                                $('#sb-update' + idList).attr("disabled", "disabled");
                                $('#sb-update' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endforeach