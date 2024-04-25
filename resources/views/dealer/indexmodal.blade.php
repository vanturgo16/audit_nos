
{{-- Modal Add --}}
<div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add New Jaringan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('jaringan.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <select class="form-select js-example-basic-single" name="type" required>
                                <option value="" selected>-- Select Type --</option>
                                <option disabled>──────────</option>
                                @foreach($type_dealer as $item)
                                    <option value="{{ $item->name_value }}" {{ old('name_value') == $item->name_value ? 'selected' : '' }}> {{ $item->name_value }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Jaringan Name</label><label style="color: darkred">*</label>
                            <input class="form-control" name="dealer_name" type="text" value="" placeholder="Input Jaringan Name.." required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Jaringan Code</label><label style="color: darkred">*</label>
                            <input class="form-control" name="dealer_code" type="text" value="" placeholder="Input Jaringan Code.." required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Jaringan address</label><label style="color: darkred">*</label>
                            <textarea class="form-control" rows="3" type="text" class="form-control" name="dealer_address" placeholder="(Input Jaringan Address, Ex. Street/Unit/Floor/No)" value="{{ old('dealer_address') }}" required></textarea>
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
                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
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