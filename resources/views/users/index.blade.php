@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-4">
                                <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-account-plus label-icon"></i> Add New User</button>
                                {{-- Modal Add --}}
                                <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Add New User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('user.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email</label><label style="color: darkred">*</label>
                                                                <div id="emailWarning"></div>
                                                                <input class="form-control" id="cek_mail" name="email" type="email" value="" placeholder="Input Email.." required>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Role</label><label style="color: darkred">*</label>
                                                                <select class="form-select js-example-basic-single" style="width: 100%" name="role" required>
                                                                    <option value="">--Select Role--</option>
                                                                    @foreach($role as $item)
                                                                        <option value="{{ $item->name_value }}">{{ $item->name_value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-account-plus label-icon"></i>Add</button>
                                                </div>
                                            </form>
                                            <script>
                                                $(document).ready(function(){
                                                    $('#cek_mail').on('input', function(){
                                                        var email = $(this).val();
                                            
                                                        checkEmailAvailability(email);
                                                    });
                                            
                                                    function checkEmailAvailability(email) {
                                                        $.ajax({
                                                            url: 'user/check_email_employee',
                                                            type: 'POST',
                                                            data: {
                                                                email: email,
                                                                _token: '{{ csrf_token() }}'
                                                            },
                                                            success: function(response) {
                                            
                                                                // console.log(response);
                                                                $('#emailWarning').remove();
                                                                if (response.status === 'notregistered') {
                                                                    $('#cek_mail').before('<div id="emailWarning"><div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert"><i class="mdi mdi-alert-outline label-icon"></i><strong>Warning</strong> - Email Not Registered As Employee<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div>');
                                                                    $('#submitButton').prop('disabled', true);
                                                                } else {
                                                                    $('#submitButton').prop('disabled', false);
                                                                }
                                                            }
                                                        });
                                                    }
                                                });
                                            </script>
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
                            <div class="col-4 d-flex justify-content-center align-items-center">
                                <h4 class="mb-sm-0 font-size-18">Manage Users</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped dt-responsive w-100" id="ssTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Name</th>
                                    <th class="align-middle text-center">Role</th>
                                    <th class="align-middle text-center">Enable 2-FA</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(function() {
        $('#ssTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('user.index') !!}',
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'name',
                    orderable: true,
                    render: function(data, type, row) {
                        return '<b>' + row.name + '</b><br>' + row.email;
                    },
                },
                {
                    orderable: true,
                    data: 'role',
                    name: 'role',
                    className: 'align-top',
                },
                {
                    data: 'is_two_fa',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data) {
                        if (data == 1) {
                            return '<i class="mdi mdi-check-circle text-success fs-5"></i>';
                        } else {
                            return '<i class="mdi mdi-close-circle text-danger fs-5"></i>';
                        }
                    }
                },
                {
                    data: 'is_active',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data) {
                        return data == 1
                            ? '<span class="badge bg-success-subtle text-success px-2 py-1">Active</span>'
                            : '<span class="badge bg-danger-subtle text-danger px-2 py-1">Inactive</span>';
                    }
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
            ],
        });
    });
</script>

@endsection