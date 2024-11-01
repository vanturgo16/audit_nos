@if($statusPeriod == 0)
    <span class="badge bg-warning text-white">Expired</span>
@else
    @php $role = Auth::user()->role; @endphp
    {{-- Internal Auditor --}}
    @if(in_array($role, ['Super Admin', 'Internal Auditor Dealer']))
        <div class="btn-group" role="group">
            <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                Action <i class="mdi mdi-chevron-down"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
                {{-- Stat Checklist Button --}}
                @if($data->status == "")
                    <li><button class="dropdown-item drpdwn" data-bs-toggle="modal" data-bs-target="#start{{ $data->id }}"><span class="mdi mdi-check-underline-circle"></span> | Start</button></li>
                @endif

                {{-- Checklist Button --}}
                @if(in_array($data->status, [0, 1, 4], true))
                    <li><a class="dropdown-item drpdwn" href="{{ route('form.checklistForm', encrypt($data->id)) }}"><span class="mdi mdi-update"></span> | Check / Update</a></li>
                @endif

                {{-- Result Button --}}
                @if(in_array($data->status, [2, 3, 5]))
                    <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#result{{ $data->id }}"><span class="mdi mdi-dns-outline"></span> | All Result</a></li>
                @endif
                
                {{-- Detail Button --}}
                @if(in_array($data->status, [5]))
                    <li><a class="dropdown-item drpdwn" href="{{ route('checklistform.detail', encrypt($data->id)) }}"><span class="mdi mdi-information"></span> | Detail</a></li>
                @endif
            </ul>
        </div>
    {{-- Assessor --}}
    @elseif($role == 'Assessor Main Dealer')
        @if(in_array($data->status, [2]))
            <a href="{{ route('assessor.reviewChecklist', encrypt($data->id)) }}"
                type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
                <i class="mdi mdi-message-draw label-icon"></i> Review
            </a>
        @elseif(in_array($data->status, [1, 3, 4, 5]))
            <a href="{{ route('assessor.reviewChecklist', encrypt($data->id)) }}"
                type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light">
                <i class="mdi mdi-information-outline label-icon"></i> Detail
            </a>
        @else 
            -
        @endif
    @endif
    
    {{-- MODAL --}}
    <div class="left-align truncate-text">
        @if(in_array($role, ['Super Admin', 'Internal Auditor Dealer']))
        {{-- Modal Start --}}
        <div class="modal fade" id="start{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Start</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('auditor.start', encrypt($data->id)) }}" id="formStart{{ $data->id }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                @if($today >= $startPeriod)
                                    <div class="col-12 text-center">
                                        <h1><span class="mdi mdi-play-circle" style="color: #FFA500;"></span></h1>
                                        <h5>Start This Checklist?</h5>
                                    </div>
                                @else
                                    <div class="col-12 text-center">
                                        <h1><span class="mdi mdi-information" style="color: #FFA500;"></span></h1>
                                        <h5>Checklist Filling Period Hasn't Started Yet</h5>
                                        <p>Will Be Able To Start On Date <b><u>{{ $startPeriod }}</u></b> Onwards</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            @if($today >= $startPeriod) <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="btnStart{{ $data->id }}"><i class="mdi mdi-play-circle label-icon"></i>Start</button> @endif
                        </div>
                    </form>
                    <script>
                        $(document).ready(function() {
                            let idList = "{{ $data->id }}";
                            $('#formStart' + idList).submit(function(e) {
                                if (!$('#formStart' + idList).valid()){
                                    e.preventDefault();
                                } else {
                                    $('#btnStart' + idList).attr("disabled", "disabled");
                                    $('#btnStart' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        @endif

    </div>
@endif