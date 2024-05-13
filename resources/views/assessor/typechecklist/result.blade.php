@if(in_array($data->status, [0, null]))
    <span class="badge bg-secondary text-white">Not Yet</span>
@else
    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#result{{ $data->id }}">View</button>
@endif

{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Result --}}
    @if(!in_array($data->status, [0, null]))
        <div class="modal fade" id="result{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Result {{ $data->type_checklist }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div><span class="fw-bold">Total Point :</span></div>
                                    <span>
                                        <span>
                                            @php
                                                $html = '';
                                                if ($data->total_point === "" || $data->total_point === null) {
                                                    foreach ($data->point as $point) {
                                                        $html .= '<span class="badge bg-info text-white">'.$point['type_response'].' : '.$point['count'].'</span><br>';
                                                    }
                                                    $html .= '<span class="badge bg-success text-white">Total Point : 0</span><br>';
                                                } else {
                                                    foreach ($data->point as $point) {
                                                        $html .= '<span class="badge bg-info text-white">'.$point['type_response'].' : '.$point['count'].'</span><br>';
                                                    }
                                                    $html .= '<span class="badge bg-success text-white">Total Point : '.$data->total_point.'</span><br>';
                                                }
                                                echo $html;
                                            @endphp
                                        </span>                                    
                                    </span>
                                </div>
                            </div>
                            <hr class="mt-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div><span class="fw-bold">Persentase Result (%) :</span></div>
                                    <span>
                                        <span>
                                        {{ $data->result_percentage.' %' }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div><span class="fw-bold">Audit Result :</span></div>
                                    <span>
                                        <span>
                                        @if($data->result_percentage == "")
                                            @php
                                                $totalPoint = 0;
                                            @endphp

                                            @foreach($data->point as $point)
                                                @if($point['type_response'] == 'Exist, Good')
                                                    @php
                                                        $totalPoint += $point['count'] * 1;
                                                    @endphp
                                                @elseif($point['type_response'] == 'Exist Not Good')
                                                    @php
                                                        $totalPoint += $point['count'] * -1;
                                                    @endphp
                                                @elseif($point['type_response'] == 'Not Exist')
                                                    @php
                                                        $totalPoint += $point['count'] * 0;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if($totalPoint != 0)
                                                @php
                                                    $result = ($totalPoint / ($data->total_checklist - $data->checklist_remaining)) * 100;
                                                    $formattedResult = number_format((float)$result, 2, '.', '');
                                                @endphp
                                            @else
                                                @php
                                                    $formattedResult = 0;
                                                @endphp
                                            @endif
                                            <!-- Total Point: {{ $totalPoint }} -->
                                            <!-- Result:  -->
                                            {{ $formattedResult }} %
                                        @else
                                            @php
                                                $formattedResult = 0;
                                            @endphp
                                        @endif

                                        @if($data->audit_result == "")
                                            @php
                                                $result_audit = "";
                                            @endphp
                                            @foreach($grading as $item)
                                                @if($formattedResult >= $item->bottom && $formattedResult <= $item->top)
                                                    @php
                                                        $result_audit = $item->result;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            {{$result_audit}}
                                        @else
                                            {{$data->audit_result}}
                                        @endif
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div><span class="fw-bold">Mandatory item :</span></div>
                                    <span>
                                        <span>
                                            @if($data->mandatory_item == "")
                                                @foreach($data->mandatory as $man)
                                                    @if($man['sgp'] != null)
                                                        Bronze
                                                    @else
                                                        @if($man['gp'] != null)
                                                            Silver
                                                        @else
                                                            @if($man['p'] != null)
                                                                Gold
                                                            @else
                                                                Platinum
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @else
                                            {{$data->mandatory_item}}
                                            @endif
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div><span class="fw-bold">Result Final :</span></div>
                                    <span>
                                        <span>
                                            {{$data->result_final}}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>