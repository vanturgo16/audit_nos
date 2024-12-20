@if($period->is_active == 1 && $period->status == 0)
    <a href="{{ route('assignchecklist.type', ['id' => encrypt($period->id), 'type' => $data->name_value]) }}"
        type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
        <i class="mdi mdi-checkbox-marked-circle-plus-outline label-icon"></i> Add Checklist
    </a>
@else
    <a href="{{ route('assignchecklist.type', ['id' => encrypt($period->id), 'type' => $data->name_value]) }}"
        type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
        <i class="mdi mdi-information-outline label-icon"></i> Detail
    </a>
@endif