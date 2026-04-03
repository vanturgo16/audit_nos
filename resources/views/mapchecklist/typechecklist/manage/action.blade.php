<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete{{ $data->id }}" title="Remove this mapping checklist">
    <i class="mdi mdi-close-circle label-icon"></i>
</button>

<div class="left-align truncate-text">
    <div class="modal fade" id="delete{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Remove Mapping</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('mapchecklist.deleteChecklist', encrypt($data->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" style="font-size: medium">
                        <div class="row">
                            <p class="text-center">Are You Sure to Remove this mapping checklist?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                            <i class="mdi mdi-delete label-icon"></i>Remove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>