<button type="button" class="btn btn-danger waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#delete{{ $data->id }}">
    <i class="mdi mdi-delete-alert label-icon"></i> Delete
</button>


{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Delete --}}
    <div class="modal fade" id="delete{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('checklist.markdelete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">Are You Sure to Delete this?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-delete{{ $data->id }}"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        let idList = "{{ $data->id }}";
                        $('#formdelete' + idList).submit(function(e) {
                            if (!$('#formdelete' + idList).valid()){
                                e.preventDefault();
                            } else {
                                $('#sb-delete' + idList).attr("disabled", "disabled");
                                $('#sb-delete' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>