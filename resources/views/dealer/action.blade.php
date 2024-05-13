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

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Info --}}
    <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Info Jaringan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">Jaringan Name :</span></div>
                                <span>
                                    <span>{{ $data->dealer_name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">Created At :</span></div>
                                <span>
                                    <span>{{ $data->created_at }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="form-group">
                                <div><span class="fw-bold">Address :</span></div>
                                <span>
                                    <span>{{ $data->dealer_address }}, {{$data->subdistrict}}, {{$data->district}}, {{$data->city}}, {{$data->province}}, {{$data->postal_code}}</span>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Jaringan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('jaringan.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <select class="form-select js-example-basic-single" style="width: 100%" name="type" required>
                                    <option value="" selected>-- Select Type --</option>
                                    <option disabled>──────────</option>
                                    @foreach($type_dealer as $item)
                                        <option value="{{ $item->name_value }}" @if($data->type == $item->name_value) selected="selected" @endif> {{ $item->name_value }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Jaringan Name</label><label style="color: darkred">*</label>
                                <input class="form-control" name="dealer_name" type="text" value="{{ $data->dealer_name }}" placeholder="Input Jaringan Name.." required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Jaringan Code</label><label style="color: darkred">*</label>
                                <input class="form-control" name="dealer_code" type="text" value="{{ $data->dealer_code }}" placeholder="Input Jaringan Code.." required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Jaringan Address</label><label style="color: darkred">*</label>
                                <textarea class="form-control" rows="3" type="text" class="form-control" name="dealer_address" placeholder="(Input Jaringan Address, Ex. Street/Unit/Floor/No)" value="{{ old('dealer_address') }}" required>{{ $data->dealer_address }}</textarea>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <select class="form-select js-example-basic-single" style="width: 100%" name="province" id="province{{ $data->id }}" class="form-control" required>
                                    <option value="" selected>-- Select Province --</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province['nama'] }}"
                                        data-idProv="{{ $province['id'] }}" @if($data->province == $province['nama']) selected="selected" @endif>
                                        {{ $province['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <select class="form-select js-example-basic-single" style="width: 100%" name="city" id="city{{ $data->id }}" class="form-control" required>
                                    <option value="" selected>- Select City -</option>
                                    <option value="{{ $data->city }}" selected>{{ $data->city }}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <select class="form-select js-example-basic-single" style="width: 100%" name="district" id="district{{ $data->id }}" class="form-control" required>
                                    <option value="" selected>- Select District -</option>
                                    <option value="{{ $data->district }}" selected>{{ $data->district }}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <select class="form-select js-example-basic-single" style="width: 100%" name="subdistrict" id="subdistrict{{ $data->id }}" class="form-control" required>
                                    <option value="" selected>- Select Subdistrict -</option>
                                    <option value="{{ $data->subdistrict }}" selected>{{ $data->subdistrict }}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <input name="zipcode" id="zipcode{{ $data->id }}" type="text" class="form-control" placeholder="Input Postal Code" value="{{ $data->postal_code }}" required>
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

    {{-- Regional --}}
    <script type="text/javascript">
        $(document).ready(function() {
            initializeSelects('{{ $data->id }}');
            // Function to initialize the select elements based on their IDs
            function initializeSelects(id) {
                // getCitybyProvince
                $('#province' + id).on('change', function() {
                    var idProv = $(this).find('option:selected').attr('data-idProv');
                    var url = '{{ route("mappingCity", ":id") }}';
                    url = url.replace(':id', idProv);
                    if (idProv) {
                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $('#city' + id).empty();
                                $('#city' + id).append(
                                    '<option value="" selected>- Choose City-</option>'
                                );

                                $.each(data, function(div, value) {
                                    $('#city' + id).append(
                                        '<option value="' +
                                        value.nama + '" data-idCity="' + value.id +
                                        '">' + value.nama + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#city' + id).empty();
                    }
                });

                // getDistrictbyCity
                $('#city' + id).on('change', function() {
                    var idCity = $(this).find('option:selected').attr('data-idCity');
                    var url = '{{ route("mappingDistrict", ":id") }}';
                    url = url.replace(':id', idCity);
                    if (idCity) {
                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $('#district' + id).empty();
                                $('#district' + id).append(
                                    '<option value="" selected>- Choose District-</option>'
                                );

                                $.each(data, function(div, value) {
                                    $('#district' + id).append(
                                        '<option value="' +
                                        value.nama + '" data-idDistrict="' + value.id +
                                        '">' + value.nama + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#district' + id).empty();
                    }
                });

                // getSubDistrictbyDistrict
                $('#district' + id).on('change', function() {
                    var idDistrict = $(this).find('option:selected').attr('data-idDistrict');
                    var url = '{{ route("mappingSubDistrict", ":id") }}';
                    url = url.replace(':id', idDistrict);
                    if (idDistrict) {
                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $('#subdistrict' + id).empty();
                                $('#subdistrict' + id).append(
                                    '<option value="" selected>- Choose District-</option>'
                                );

                                $.each(data, function(div, value) {
                                    $('#subdistrict' + id).append(
                                        '<option value="' +
                                        value.nama + '" data-zipcode="' + value
                                        .kodepos + '">' + value.nama + '</option>');
                                });
                            }
                        });
                    } else {
                        $('#subdistrict' + id).empty();
                    }
                });

                // zipcode
                $('#subdistrict' + id).on('change', function() {
                    var zipcode = $(this).find('option:selected').attr('data-zipcode');
                    console.log(zipcode);
                    $('#zipcode' + id).val(zipcode);
                });
            }
        });
    </script>

</div>