@if($data->status == 2)
    <a href="{{ route('assessor.review', encrypt($data->id)) }}"
        type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
        <i class="mdi mdi-file-find label-icon"></i> Review
    </a>
@else
    <a href="{{ route('assessor.review', encrypt($data->id)) }}"
        type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
        <i class="mdi mdi-check-underline-circle label-icon"></i> Detail
    </a>
@endif