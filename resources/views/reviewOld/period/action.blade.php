@php $role = Auth::user()->role; @endphp


@if(in_array($role, ['Super Admin', 'Admin', 'PIC Dealers']))
    <a href="{{ route('review.periodDetail', encrypt($data->id)) }}"
        type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
        <i class="mdi mdi-information-outline label-icon"></i> Detail
    </a>

@elseif(in_array($role, ['Assessor Main Dealer']))
    @if($data->status == 3)
        <a href="{{ route('review.periodDetail', encrypt($data->id)) }}"
            type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
            <i class="mdi mdi-message-draw label-icon"></i> Review
        </a>
    @else
        <a href="{{ route('review.periodDetail', encrypt($data->id)) }}"
            type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
            <i class="mdi mdi-information-outline label-icon"></i> Detail
        </a>
    @endif
    
@elseif(in_array($role, ['PIC NOS MD']))
    @if($data->status == 4)
        <a href="{{ route('review.periodDetail', encrypt($data->id)) }}"
            type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
            <i class="mdi mdi-message-draw label-icon"></i> Review
        </a>
    @else
        <a href="{{ route('review.periodDetail', encrypt($data->id)) }}"
            type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
            <i class="mdi mdi-information-outline label-icon"></i> Detail
        </a>
    @endif
@endif

