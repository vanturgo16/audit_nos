@if($statusPeriod == 0)
    <span class="badge bg-warning text-white">Expired</span>
@else
    @php $role = Auth::user()->role; @endphp
    {{-- Internal Auditor --}}
    @if(in_array($role, ['Super Admin', 'Admin']))
        <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
    {{-- Assessor --}}
    @elseif($role == 'Assessor Main Dealer')
        @if(in_array($data->status, [2]))
            <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-primary">Review</a>
        @elseif(in_array($data->status, [1, 5]))
            <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
        @else 
            <span class="badge bg-warning text-white"><i class="mdi mdi-refresh label-icon"></i></span>
        @endif
    {{-- PIC NOS MD --}}
    @elseif($role == 'PIC NOS MD')
        @if(in_array($data->status, [3]))
            <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-primary">Review</a>
        @elseif(in_array($data->status, [1, 5]))
            <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
        @else 
            <span class="badge bg-warning text-white"><i class="mdi mdi-refresh label-icon"></i></span>
        @endif
    @endif
@endif