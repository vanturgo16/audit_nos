@php $role = Auth::user()->role; @endphp

@if(in_array($role, ['Super Admin', 'Internal Auditor Dealer']))
    <a href="{{ route('auditor.periodDetail', encrypt($data->id)) }}"
        type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
        <i class="mdi mdi-information-outline label-icon"></i> Detail
    </a>
@elseif($role == 'Assessor Main Dealer')
    <a href="{{ route('assessor.periodDetail', encrypt($data->id)) }}"
        type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
        <i class="mdi mdi-information-outline label-icon"></i> Detail
    </a>
@endif