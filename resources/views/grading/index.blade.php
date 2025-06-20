@extends('layouts.master')
@section('konten')


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-4"></div>
                            <div class="col-4 d-flex justify-content-center align-items-center">
                                <h4 class="mb-sm-0 font-size-18">Master Grading Result</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-striped dt-responsive w-100" id="ssTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Result</th>
                                    <th class="align-middle text-center">Top</th>
                                    <th class="align-middle text-center">Bottom</th>
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
            ajax: '{!! route('grading.index') !!}',
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
                    data: 'result',
                    name: 'result',
                    orderable: true,
                    className: 'align-top text-center text-bold'
                },
                {
                    data: 'top',
                    name: 'top',
                    orderable: true,
                    className: 'align-top text-center text-bold'
                },
                {
                    data: 'bottom',
                    name: 'bottom',
                    orderable: true,
                    className: 'align-top text-center text-bold'
                },
            ],
        });
    });
</script>

@endsection