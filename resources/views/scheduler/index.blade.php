@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Hit Manually Scheduler</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Configuration</a></li>
                            <li class="breadcrumb-item active">List Scheduler</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive table-bordered dt-responsive w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Scheduler Name</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="align-top text-center">1</td>
                                    <td class="align-top text-bold">
                                        Check Expired Period
                                    </td>
                                    <td class="align-top text-center">
                                        <a href="{{ route('scheduler.expiredPeriod') }}"
                                            type="button" class="btn btn-sm btn-warning waves-effect btn-label waves-light action-button">
                                            <i class="mdi mdi-bell-alert-outline label-icon"></i> Hit
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-top text-center">2</td>
                                    <td class="align-top text-bold">
                                        Check Submit Period
                                    </td>
                                    <td class="align-top text-center">
                                        <a href="{{ route('scheduler.reminderSubmitPeriod') }}"
                                            type="button" class="btn btn-sm btn-warning waves-effect btn-label waves-light action-button">
                                            <i class="mdi mdi-bell-alert-outline label-icon"></i> Hit
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $(".table-responsive").DataTable({
            responsive: true,
        });
    });
</script>
<script>
    document.querySelectorAll('.action-button').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            this.classList.add('disabled');
            this.style.pointerEvents = 'none';
            this.classList.remove("waves-effect", "btn-label", "waves-light");
            this.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Please Wait...';
            window.location.href = this.href;
        });
    });
</script>

@endsection