
@if(in_array(Auth::user()->role, ['Assessor Main Dealer', 'PIC Dealers']))
    @if($data->status == 2)
        <a href="{{ route('assessor.review', encrypt($data->id)) }}"
            type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
            <i class="mdi mdi-file-find label-icon"></i> Review
        </a>
    @else
        <a href="{{ route('assessor.review', encrypt($data->id)) }}"
            type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
            <i class="mdi mdi-information label-icon"></i> Detail
        </a>
    @endif
@else
    <a href="{{ route('assessor.review', encrypt($data->id)) }}"
        type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
        <i class="mdi mdi-information label-icon"></i> Detail
    </a>
@endif