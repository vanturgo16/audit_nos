@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Master Branch</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Branch</li>
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
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Branch</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Branch</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('branch.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <select class="form-select" name="type" required>
                                                        <option value="" selected>-- Select Type --</option>
                                                        <option disabled>──────────</option>
                                                        @foreach($type_dealer as $item)
                                                            <option value="{{ $item->id }}" {{ old('name_value') == $item->name_value ? 'selected' : '' }}> {{ $item->name_value }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Branch Name</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="dealer_name" type="text" value="" placeholder="Input Branch Name.." required>
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Branch Code</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="dealer_code" type="text" value="" placeholder="Input Branch Code.." required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 mb-3">
                                                        <label class="form-label">Branch address</label><label style="color: darkred">*</label>
                                                        <textarea class="form-control" rows="3" type="text" class="form-control" name="dealer_address" placeholder="(Input Branch Address, Ex. Street/Unit/Floor/No)" value="{{ old('dealer_address') }}" required></textarea>
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
                    </div>
                    <div class="card-body">

                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Branch Name</th>
                                    <th class="align-middle text-center">Branch Code</th>
                                    <th class="align-middle text-center">Branch Type</th>
                                    <th class="align-middle text-center">Branch Address</th>
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
                                        <td class="align-middle text-center"><b>{{ $data->dealer_code }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->type }}</b></td>
                                        <td class="align-middle text-center"><b>{{ $data->dealer_address }}</b></td>
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
                                                        <h5 class="modal-title" id="staticBackdropLabel">Info Branch</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Branch Name :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->dealer_name }}</span>
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
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Branch</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('branch.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12 mb-3">
                                                                    <select class="form-select" name="type" required>
                                                                        <option value="" selected>-- Select Type --</option>
                                                                        <option disabled>──────────</option>
                                                                        @foreach($type_dealer as $item)
                                                                            <option value="{{ $item->name_value }}" @if($data->type == $item->name_value) selected="selected" @endif> {{ $item->name_value }} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6 mb-3">
                                                                    <label class="form-label">Branch Name</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="dealer_name" type="text" value="{{ $data->dealer_name }}" placeholder="Input Branch Name.." required>
                                                                </div>
                                                                <div class="col-lg-6 mb-3">
                                                                    <label class="form-label">Branch Code</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="dealer_code" type="text" value="{{ $data->dealer_code }}" placeholder="Input Branch Code.." required>
                                                                </div>
                                                                <div class="col-lg-12 mb-3">
                                                                    <label class="form-label">Branch Address</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="dealer_address" type="text" value="{{ $data->dealer_address }}" placeholder="Input Branch Address.." required>
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