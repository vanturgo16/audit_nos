@if(in_array($data->status, [1, 2]))
    <a href="{{ route('auditor.periodDetail', encrypt($data->id)) }}"
        type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
        <i class="mdi mdi-clipboard-check label-icon"></i> Audit Checklist
    </a>
@else 
    <a href="{{ route('auditor.periodDetail', encrypt($data->id)) }}"
        type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
        <i class="mdi mdi-information-outline label-icon"></i> Detail
    </a>
@endif