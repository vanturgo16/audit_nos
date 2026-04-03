<a href="{{ route('mapchecklist.detail', ['type' => encrypt($type), 'typecheck' => encrypt($data->type_checklist)]) }}"
    type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
    <i class="mdi mdi-cog label-icon"></i> Manage
</a>
<button type="button" class="btn btn-sm btn-danger waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#delete{{ $data->idUnique }}">
    <i class="mdi mdi-close-circle label-icon"></i> Remove
</button>

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Delete --}}
    <div class="modal fade" id="delete{{ $data->idUnique }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Remove Type Checklist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('mapchecklist.deletetype', encrypt($data->type_checklist)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $type }}" name="typeJaringan">
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Remove</b> This Type Checklist In This Mapping?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light">
                            <i class="mdi mdi-close-circle label-icon"></i>Remove
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/formLoad.js') }}"></script>