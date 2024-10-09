@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('checklist.index', $parent->type_checklist) }}">List Checklist (Type: {{ $parent->type_checklist }})</a></li>
                            <li class="breadcrumb-item active">Exchange Order Number</li>
                        </ol>
                    </div>
                    <div class="page-title-right">
                        <a id="backButton" type="button" href="{{ route('checklist.index', $parent->type_checklist) }}"
                            class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left-circle label-icon"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <form action="{{ route('checklist.exc_order.update', encrypt($checklist->id)) }}" id="formupdate" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center py-3">
                            <h5 class="mb-0">Exchange Order Number for {{ "'" . $checklist->order_no . " - " . $checklist->sub_point_checklist . "'" }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" name="type_checklist_before" value="{{ $parent->type_checklist }}">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Type Checklist</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_checklist" id="type_checklist" required>
                                        <option value="" selected>-- Select Type --</option>
                                        <option disabled>──────────</option>
                                        @foreach($type_checklist as $item)
                                            <option value="{{ $item->name_value }}" @if($parent->type_checklist == $item->name_value) selected="selected" @endif> {{ $item->name_value }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Parent Point</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="parent_point_checklist" id="parentPoint" required>
                                        <option value="" selected>-- Select Parent --</option>
                                        <option disabled>──────────</option>
                                        @foreach( $type_parent as $item)
                                            <option value="{{ $item->id }}" @if($checklist->id_parent_checklist == $item->id) selected="selected" @endif> {{ $item->parent_point_checklist }} </option>
                                        @endforeach
                                        <option disabled>──────────</option>
                                        <option class="font-weight-bold" value="AddParent">Add New Parent</option>
                                    </select>
                                </div>
                                <script>
                                    // getParentList
                                    $('select[name="type_checklist"]').on('change', function() {
                                        var typeChecklist = $(this).find('option:selected').val();

                                        if (typeChecklist === 'H1 Premises') {
                                            $('#guidechceklist').show();
                                            $('input[name="guide_checklist"]').attr("required", true);
                                        } else {
                                            $('#guidechceklist').hide();
                                            $('input[name="guide_checklist"]').attr("required", false);
                                        }

                                        var url = '{{ route("mappingParent", ":name") }}';
                                        url = url.replace(':name', typeChecklist);
                                        
                                        if (typeChecklist) {
                                            $.ajax({
                                                url: url,
                                                type: "GET",
                                                dataType: "json",
                                                success: function(data) {
                                                    $('select[id="parentPoint"]').empty();
                                                    $('select[id="order_no"]').empty();

                                                    $('select[id="parentPoint"]').append(
                                                        '<option value="" disabled selected>-- Select Parent --</option>'+
                                                        '<option disabled>──────────</option>'
                                                    );
                                                    $('select[id="order_no"]').append(
                                                        '<option value="" disabled selected>-- Select Order Number --</option>'+
                                                        '<option disabled>──────────</option>'
                                                    );

                                                    $.each(data, function(div, value) {
                                                        $('select[id="parentPoint"]').append(
                                                            '<option value="' + value.id + '">' + value.parent_point_checklist + '</option>');
                                                    });
                                                    $('select[id="parentPoint"]').append(
                                                        '<option disabled>──────────</option>' +
                                                        '<option class="font-weight-bold" value="AddParent">Add New Parent</option>'
                                                    );
                                                }
                                            });
                                        } else {
                                            $('select[id="parentPoint"]').empty();
                                            $('select[id="order_no"]').empty();
                                        }
                                    });
                                </script>
                            </div>
                            <div class="row">
                                    <input type="hidden" name="order_current" value="{{ $checklist->order_no }}">
                                    <input type="hidden" name="type_checklist_current" value="{{ $checklist->type_checklist }}">
                                    <input type="hidden" name="parent_point_checklist_current" value="{{ $checklist->id_parent_checklist }}">
                                    <div class="col-lg-6 mb-3">
                                    <label class="form-label">Exchange Order Number</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="order_no" id="order_no">
                                            <option value="0">Change order to First</option>
                                            <option value="99999">Change order to Last</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->order_no }}" @if($checklist->order_no == $order->order_no) selected="selected" @endif> {{ $order->order_no . "-" . $order->sub_point_checklist }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12 align-right">
                                    <a href="{{ route('checklist.index', $parent->type_checklist) }}" type="button" class="btn btn-light waves-effect btn-label waves-light">
                                        <i class="mdi mdi-arrow-left-circle label-icon"></i>Back
                                    </a>
                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb">
                                        <i class="mdi mdi-update label-icon"></i>Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Validation Form --}}
<script>
    document.getElementById('formupdate').addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            return false;
        }
        var submitButton = this.querySelector('button[name="sb"]');
        submitButton.disabled = true;
        submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
        return true;
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {    
        $('#parentPoint').change(function() {
            var parentPoint = $(this).val();
            var typeChecklist = $('#type_checklist').val(); 
            var url = '{{ route("mappingOrderNoChecklist", [":parentPoint", ":type"]) }}';
            url = url.replace(':parentPoint', parentPoint).replace(':type', typeChecklist);
            //alert(url);
            if(typeChecklist) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        //alert(data);
                        //console.log("AJAX Success Data:", gedungId); // Debugging
                        $('#order_no').empty();
                        $('#order_no').append('<option value="0">Change order to First</option>');
                        $('#order_no').append('<option value="99999">Change order to Last</option>');
                        $.each(data, function(key, value) {
                            $('#order_no').append('<option value="' + value.order_no + '">' + value.order_no + ' - ' + value.sub_point_checklist + '</option>');
                        });
                    }
                });
            } else {
                $('#order_no').empty();
            }
        });
    })
</script>

@endsection