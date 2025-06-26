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
            @if($idAssesor)
                @if(Auth::user()->id == $idAssesor)
                    <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-primary">Review</a>
                @else
                    <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
                @endif
            @else
                <a href="#" data-bs-toggle="modal" data-bs-target="#takereview{{ $data->id }}" type="button" class="btn btn-sm btn-info">Take Review</a>
                {{-- Modal Start --}}
                <div class="modal fade" id="takereview{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-top" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Take Review</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('review.takeReview', encrypt($data->id_periode)) }}" id="formTakeReview{{ $data->id }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <h1><span class="mdi mdi-pen text-primary"></span></h1>
                                            <h5>Take This Checklist For Review By You?</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" id="btnTakeReview{{ $data->id }}">Ok</button>
                                </div>
                            </form>
                            <script>
                                $(document).ready(function() {
                                    let idList = "{{ $data->id }}";
                                    $('#formTakeReview' + idList).submit(function(e) {
                                        if (!$('#formTakeReview' + idList).valid()){
                                            e.preventDefault();
                                        } else {
                                            $('#btnTakeReview' + idList).attr("disabled", "disabled");
                                            $('#btnTakeReview' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
        {{-- @elseif(in_array($data->status, [1, 5]))
            <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
        @else 
            <span class="badge bg-warning text-white"><i class="mdi mdi-refresh label-icon"></i></span> --}}
        @endif
    {{-- PIC NOS MD --}}
    @elseif($role == 'PIC NOS MD')
        @if(in_array($data->status, [3]))
            <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-primary">Review</a>
        @else
            <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
        {{-- @elseif(in_array($data->status, [1, 5]))
            <a href="{{ route('review.reviewChecklist', encrypt($data->id)) }}" type="button" class="btn btn-sm btn-info">Detail</a>
        @else 
            <span class="badge bg-warning text-white"><i class="mdi mdi-refresh label-icon"></i></span> --}}
        @endif
    @endif
@endif