@extends('layouts.master')
@section('konten')

{{-- All Modal --}}
@include('dealer.indexmodal')

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
<script src="{{ asset('assets/js/regional.js') }}"></script>
@foreach ($datas as $data)
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
@endforeach

@endsection