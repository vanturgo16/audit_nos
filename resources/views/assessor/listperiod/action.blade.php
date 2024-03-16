@if (in_array($data->status, [0, 1, 2]))
    <span class="badge bg-secondary text-white">Not Completed</span>
@else
    <a href="{{ route('assessor.typechecklist', encrypt($data->id)) }}"
        type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
        <i class="mdi mdi-check-underline-circle label-icon"></i> Checklist
    </a>
@endif