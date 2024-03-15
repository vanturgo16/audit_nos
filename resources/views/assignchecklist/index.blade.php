@extends('layouts.master')

@section('konten')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between py-0 mb-3">
                    <h4 class="mb-sm-0 font-size-25">
                        Assign Checklist
                    </h4>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('periodchecklist.index') }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>Period</b></td>
                            <td class="align-middle">: {{ $period->period }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b><i>Jaringan</i> Name</b></td>
                            <td class="align-middle">: {{ $period->dealer_name }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Date</b></td>
                            <td class="align-middle">: {{ Carbon\Carbon::parse($period->start_date)->format('d-m-Y') }} <b> Until </b>{{ Carbon\Carbon::parse($period->end_date)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Status</b></td>
                            <td class="align-middle">: 
                                @if($period->status == 0)
                                    <span class="badge bg-danger text-white">Inactive</span>
                                @elseif($period->status == 1)
                                    <span class="badge bg-success text-white">Active</span>
                                @elseif($period->status == 2)
                                    <span class="badge bg-success text-white">Active</span>
                                @elseif($period->status == 3)
                                    <span class="badge bg-success text-white">Active</span> <span class="badge bg-info text-white">Complete</span>
                                @elseif($period->status == 4)
                                    <span class="badge bg-danger text-white">Closed Approved</span>
                                @elseif($period->status == 5)
                                    <span class="badge bg-success text-white">Active</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Active</b></td>
                            <td class="align-middle">: 
                                @if ($period->is_active == 1)
                                    <span class="badge bg-success text-white">Active</span>
                                @else
                                    <span class="badge bg-danger text-white">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <div class="card">
                    @if($period->is_active == 0 && $check == 1)
                        <div class="card-header d-flex justify-content-end">
                            <button type="button" class="btn btn-success waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#submit"><i class="mdi mdi-check-bold label-icon"></i> Submit</button>
                            {{-- Modal Submit --}}
                            <div class="modal fade" id="submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Submit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('assignchecklist.submit', encrypt($period->id)) }}" id="formsubmit" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <p>
                                                        Start submit this checklist? 
                                                        (You are not longer to edit this checklist!)
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-check-bold label-icon"></i>Submit</button>
                                            </div>
                                        </form>
                                        <script>
                                            document.getElementById('formsubmit').addEventListener('submit', function(event) {
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
                    @endif
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="server-side-table">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Type Checklist</th>
                                    <th class="align-middle text-center">Total Assign Checklist</th>
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
        $('#server-side-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('assignchecklist.index', encrypt($period->id)) !!}',
            columns: [{
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
                {
                    orderable: true,
                    searchable: true,
                    data: 'name_value',
                    name: 'name_value',
                    className: 'align-middle text-bold',
                },
                {
                    orderable: true,
                    searchable: true,
                    data: 'count',
                    className: 'align-middle text-center text-bold',
                    render: function(data, type, row) {
                        var html
                        if(row.count == 0){
                            html = '<span class="badge bg-secondary text-white">Not Set</span>';
                        } else {
                            html = '<h5><span class="badge bg-success text-white">' + row.count + '</span></h5>';
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
            ],
        });
    });

</script>
@endsection