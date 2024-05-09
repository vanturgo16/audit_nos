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
                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_dealer" required>
                                <option value="" selected>-- Select Dealer --</option>
                                <option disabled>──────────</option>
                                @foreach( $dealer as $item)
                                    <option value="{{ $item->id }}" {{ old('dealer_name') == $item->dealer_name ? 'selected' : '' }}> {{ $item->dealer_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_dept" id="selectdepartment" required>
                                <option value="" selected>-- Select department --</option>
                                <option disabled>──────────</option>
                                @foreach( $department as $item)
                                    <option value="{{ $item->id }}" {{ old('department_name') == $item->department_name ? 'selected' : '' }}> {{ $item->department_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_position" id="selectPosition" required>
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
                            <select class="form-select js-example-basic-single" style="width: 100%" name="province" id="province" class="form-control" required>
                                <option value="" selected>-- Select Province --</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province['nama'] }}"
                                    data-idProv="{{ $province['id'] }}">
                                    {{ $province['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" style="width: 100%" name="city" id="city" class="form-control" required>
                                <option value="" selected>- Select City -</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" style="width: 100%" name="district" id="district" class="form-control" required>
                                <option value="" selected>- Select District -</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <select class="form-select js-example-basic-single" style="width: 100%" name="subdistrict" id="subdistrict" class="form-control" required>
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

{{-- Mapping Department Position --}}
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

{{-- Check Email --}}
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
                        $('#cek_mail').after('<p id="emailWarning" style="color: darkred;">Email Already Used.</p>');
                        $('#submitButton').prop('disabled', true);
                    } else {
                        $('#submitButton').prop('disabled', false);
                    }
                }
            });
        }
    });
</script>

{{-- Regional --}}
<script>
    // getCitybyProvince
    $('select[id="province"]').on('change', function() {
        var idProv = $(this).find('option:selected').attr('data-idProv');
        var url = '{{ route("mappingCity", ":id") }}';
        url = url.replace(':id', idProv);
        console.log(url);
        if (idProv) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('select[id="city"]').empty();
                    $('select[id="city"]').append(
                        '<option value="" selected>- Choose City-</option>'
                    );

                    $.each(data, function(div, value) {
                        $('select[id="city"]').append(
                            '<option value="' +
                            value.nama + '" data-idCity="' + value.id +
                            '">' + value.nama + '</option>');
                    });
                }
            });
        } else {
            $('select[id="city"]').empty();
        }
    });

    // getDistrictbyCity
    $('select[id="city"]').on('change', function() {
        var idCity = $(this).find('option:selected').attr('data-idCity');
        var url = '{{ route("mappingDistrict", ":id") }}';
        url = url.replace(':id', idCity);
        if (idCity) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('select[id="district"]').empty();
                    $('select[id="district"]').append(
                        '<option value="" selected>- Choose District-</option>'
                    );

                    $.each(data, function(div, value) {
                        $('select[id="district"]').append(
                            '<option value="' +
                            value.nama + '" data-idDistrict="' + value.id +
                            '">' + value.nama + '</option>');
                    });
                }
            });
        } else {
            $('select[id="district"]').empty();
        }
    });

    // getSubDistrictbyDistrict
    $('select[id="district"]').on('change', function() {
        var idDistrict = $(this).find('option:selected').attr('data-idDistrict');
        var url = '{{ route("mappingSubDistrict", ":id") }}';
        url = url.replace(':id', idDistrict);
        if (idDistrict) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('select[id="subdistrict"]').empty();
                    $('select[id="subdistrict"]').append(
                        '<option value="" selected>- Choose District-</option>'
                    );

                    $.each(data, function(div, value) {
                        $('select[id="subdistrict"]').append(
                            '<option value="' +
                            value.nama + '" data-zipcode="' + value
                            .kodepos + '">' + value.nama + '</option>');
                    });
                }
            });
        } else {
            $('select[id="subdistrict"]').empty();
        }
    });

    // zipcode
    $('select[id="subdistrict"]').on('change', function() {
        var zipcode = $(this).find('option:selected').attr('data-zipcode');
        console.log(zipcode);
        $('input[id="zipcode"]').val(zipcode);
    });
</script>