@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Master Employee</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Employee</li>
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
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New employee</button>
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
                                                    <select class="form-select" name="id_dealer" required>
                                                        <option value="" selected>-- Select Dealer --</option>
                                                        <option disabled>──────────</option>
                                                        @foreach( $dealer as $item)
                                                            <option value="{{ $item->id }}" {{ old('dealer_name') == $item->dealer_name ? 'selected' : '' }}> {{ $item->dealer_name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select" name="id_dept" id="selectdepartment" required>
                                                        <option value="" selected>-- Select department --</option>
                                                        <option disabled>──────────</option>
                                                        @foreach( $department as $item)
                                                            <option value="{{ $item->id }}" {{ old('department_name') == $item->department_name ? 'selected' : '' }}> {{ $item->department_name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select" name="id_position" id="selectPosition" required>
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
                                                    <select class="form-select" name="province" id="province" class="form-control" required>
                                                        <option value="" selected>-- Select Province --</option>
                                                        @foreach ($provinces as $province)
                                                            <option value="{{ $province['nama'] }}"
                                                            data-idProv="{{ $province['id'] }}">
                                                            {{ $province['nama'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select" name="city" id="city" class="form-control" required>
                                                        <option value="" selected>- Select City -</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select" name="district" id="district" class="form-control" required>
                                                        <option value="" selected>- Select District -</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <select class="form-select" name="subdistrict" id="subdistrict" class="form-control" required>
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
                    </div>
                    <div class="card-body">

                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Dealer</th>
                                    <th class="align-middle text-center">Department</th>
                                    <th class="align-middle text-center">Position</th>
                                    <th class="align-middle text-center">Employee Name</th>
                                    <th class="align-middle text-center">Employee Email</th>
                                    <th class="align-middle text-center">Employee NIK</th>
                                    <th class="align-middle text-center">Employee Telephone</th>
                                    <th class="align-middle text-center">Employee Address</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0;?> 
                                @foreach ($datas as $data)
                                <?php $no++ ;?>
                                    <tr>
                                        <td class="align-middle text-center">{{ $no }}</td>
                                        <td class="align-middle text-center"><b>{{ $data->dealer_name }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->department_name }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->position_name }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->employee_name }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->email }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->employee_nik }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->employee_telephone }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->employee_address }}</b></td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
                                                    <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>
                                                    <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        
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
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12 mb-3">
                                                                    <select class="form-select" name="id_dealer" required>
                                                                        <option value="" selected>-- Select Dealer --</option>
                                                                        <option disabled>──────────</option>
                                                                        @foreach( $dealer as $item)
                                                                            <option value="{{ $item->id }}" @if($data->id_dealer == $item->id) selected="selected" @endif> {{ $item->dealer_name }} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6 mb-3">
                                                                    <select class="form-select" name="id_dept" id="selecteditDepartment{{ $data->id }}" required>
                                                                        <option value="" selected>-- Select Department --</option>
                                                                        <option disabled>──────────</option>
                                                                        @foreach( $department as $item)
                                                                            <option value="{{ $item->id }}" @if($data->id_dept == $item->id) selected="selected" @endif> {{ $item->department_name }} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6 mb-3">
                                                                    <select class="form-select" name="id_position" id="selecteditPosition{{ $data->id }}" required>
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
                                                                        <select class="form-select" name="province" id="province" class="form-control" required>
                                                                            <option value="" selected>-- Select Province --</option>
                                                                            @foreach ($provinces as $province)
                                                                                <option value="{{ $province['nama'] }}"
                                                                                data-idProv="{{ $province['id'] }}" @if($data->province == $province['nama']) selected="selected" @endif>
                                                                                {{ $province['nama'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-6 mb-3">
                                                                        <select class="form-select" name="city" id="city" class="form-control" required>
                                                                            <option value="" selected>- Select City -</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-6 mb-3">
                                                                        <select class="form-select" name="district" id="district" class="form-control" required>
                                                                            <option value="" selected>- Select District -</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-6 mb-3">
                                                                        <select class="form-select" name="subdistrict" id="subdistrict" class="form-control" required>
                                                                            <option value="" selected>- Select Subdistrict -</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-6 mb-3">
                                                                        <input name="zipcode" id="zipcode" type="text" class="form-control" placeholder="Input Postal Code" required>
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

<script>
    // add
    $(document).ready(function(){
        $('#selectdepartment').change(function(){
            var idDept = $(this).val();
            $.ajax({
                url: '/json_position/' + idDept,
                type: 'GET',
                success: function(data) {
                    $('#selectPosition').empty();
                    // console.log(data);
                    $('#selectPosition').append('<option value="" disabled selected>-- Select Position --</option>');
                    $('#selectPosition').append('<option value=""disabled>──────────</option>');
                    $.each(data, function(index, value) {
                        $('#selectPosition').append('<option value="' + value.id + '">' + value.position_name + '</option>');
                    });
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function(){
        $('#cek_mail').on('input', function(){
            var email = $(this).val();

            checkEmailAvailability(email);
        });

        function checkEmailAvailability(email) {
            $.ajax({
                url: '/check_email_employee',
                type: 'POST',
                data: {
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {

                    // console.log(response);
                    $('#emailWarning').remove();
                    if (response.status === 'used') {
                        $('#cek_mail').after('<p id="emailWarning" style="color: darkred;">Email sudah terpakai.</p>');
                        $('#submitButton').prop('disabled', true);
                    } else {
                        $('#submitButton').prop('disabled', false);
                    }
                }
            });
        }
    });
</script>

@foreach ($datas as $datam)
<script>
    //edit
    $(document).ready(function(){
        function loadPositions(idDept) {
            $.ajax({
                url: '/json_position/' + idDept,
                type: 'GET',
                
                success: function(data) {
                    $('#selecteditPosition{{ $datam->id }}').empty();
                    $.each(data, function(key, value) {
                        var id_position = '{{$datam->id_position}}'
                        $('#selecteditPosition{{ $datam->id }}').append('<option value="' + value.id + '" ' + (id_position == value.id ? 'selected="selected"' : '') + '>' + value.position_name + '</option>');
                    });
                }
            });
        }
        $('#selecteditDepartment{{ $datam->id }}').change(function(){
            var idDept = $(this).val();
            loadPositions(idDept);
        });

        loadPositions($('#selecteditDepartment{{ $datam->id }}').val());
    });
</script>
@endforeach

{{-- Script Regional --}}
<script type="text/javascript">
    $(document).ready(function() {
        // getCitybyProvince
        $('select[name="province"]').on('change', function() {
            var idProv = $(this).find('option:selected').attr('data-idProv');
            var url = '{{ route("mappingCity", ":id") }}';
            url = url.replace(':id', idProv);
            if (idProv) {
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="city"]').empty();
                        $('select[name="city"]').append(
                            '<option value="" selected>- Choose City-</option>'
                        );

                        $.each(data, function(div, value) {
                            $('select[name="city"]').append(
                                '<option value="' +
                                value.nama + '" data-idCity="' + value.id +
                                '">' + value.nama + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="city"]').empty();
            }
        });

        // getDistrictbyCity
        $('select[name="city"]').on('change', function() {
            var idCity = $(this).find('option:selected').attr('data-idCity');
            var url = '{{ route("mappingDistrict", ":id") }}';
            url = url.replace(':id', idCity);
            if (idCity) {
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="district"]').empty();
                        $('select[name="district"]').append(
                            '<option value="" selected>- Choose District-</option>'
                        );

                        $.each(data, function(div, value) {
                            $('select[name="district"]').append(
                                '<option value="' +
                                value.nama + '" data-idDistrict="' + value.id +
                                '">' + value.nama + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="district"]').empty();
            }

        });

        // getSubDistrictbyDistrict
        $('select[name="district"]').on('change', function() {
            var idDistrict = $(this).find('option:selected').attr('data-idDistrict');
            var url = '{{ route("mappingSubDistrict", ":id") }}';
            url = url.replace(':id', idDistrict);
            if (idDistrict) {
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="subdistrict"]').empty();
                        $('select[name="subdistrict"]').append(
                            '<option value="" selected>- Choose District-</option>'
                        );

                        $.each(data, function(div, value) {
                            $('select[name="subdistrict"]').append(
                                '<option value="' +
                                value.nama + '" data-zipcode="' + value
                                .kodepos + '">' + value.nama + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="subdistrict"]').empty();
            }
        });

        // zipcode
        $('select[name="subdistrict"]').on('change', function() {
            var zipcode = $(this).find('option:selected').attr('data-zipcode');
            console.log(zipcode);
            $('#zipcode').val(zipcode)
        });

    });

</script>

@endsection