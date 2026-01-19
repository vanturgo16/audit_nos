@if($data->status == 0)
    <div class="text-center">
        <a href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"
            type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
            <i class="mdi mdi-pencil-outline label-icon"></i> Update
        </a>
    </div>

    <div class="modal fade" id="update{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Update Assessor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="formLoad" action="{{ route('periodname.updateAssesorAssign', encrypt($data->id)) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="assessors{{ $data->id }}" class="form-label">Select Assessors</label>
                                <select name="assessors[]" id="assessors{{ $data->id }}" class="form-control select2" multiple style="width:100%">
                                    @foreach($listAssesor as $assessor)
                                        <option value="{{ $assessor->id }}"
                                            {{ in_array($assessor->id, json_decode($data->assesor_ids, true) ?: []) ? 'selected' : '' }}>
                                            {{ $assessor->email }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-update label-icon"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('assets/js/formLoad.js') }}"></script>

@else
    <div class="text-center">
        <button type="button" class="btn btn-sm btn-secondary waves-effect btn-label waves-light" disabled>
            <i class="mdi mdi-pencil-outline label-icon"></i> Update
        </button>
    </div>
@endif
