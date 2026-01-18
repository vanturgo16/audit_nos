<div class="modal-header">
    <h5 class="modal-title" id="staticBackdropLabel">Log Summary History Response & Correction</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body md-body-scroll">
    @if($summCheckJar)
        <div class="row">
            @php
                function getBadge($value) {
                    if (!$value) return '-';
                    $baseClass = "badge rounded px-2 py-1";
                    switch ($value) {
                        case 'Bronze':
                            $style = 'background-color: #cd7f32; color: white;';
                            break;
                        case 'Silver':
                            $style = 'background-color: #c0c0c0; color: black;';
                            break;
                        case 'Gold':
                            $style = 'background-color: #ffd700; color: black;';
                            break;
                        case 'Platinum':
                            $style = 'background-color: #e5e4e2; color: black;';
                            return "
                                <div class='badge-wrapper d-inline-block position-relative' style='margin-top: -0.3rem; margin-bottom: -0.3rem;'>
                                    <span class='$baseClass' style='$style'>$value</span>
                                    <span class='shine-overlay'></span>
                                </div>
                            ";
                        default:
                            $style = 'background-color: #f8f9fa; color: black;';
                    }
                    return "<span class='$baseClass' style='$style'>$value</span>";
                }
            @endphp
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header text-center p-0">
                        <h6 class="fw-bold mt-2">Result</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="mb-0 w-100" style="border-collapse: separate;">
                            <tbody>
                                <tr>
                                    <th class="align-top px-3 w-50" style="border-right: 0.5px solid;"><small>Before</small></th>
                                    <th class="align-top px-3 w-50"><small>After</small></th>
                                </tr>
                                <tr>
                                    <td class="align-top px-3" style="border-right: 0.5px solid;">
                                        <h3 class="fw-bold text-info">{{ $summCheckJar->log_result_percentage ? $summCheckJar->log_result_percentage . '%' : '-' }}</h3>
                                    </td>
                                    <td class="align-top px-3">
                                        <h3 class="fw-bold text-success">{{ $summCheckJar->log_result_percentage_assesor ? $summCheckJar->log_result_percentage_assesor . '%' : '-' }}</h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header text-center p-0">
                        <h6 class="fw-bold mt-2">Result Audit</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="mb-0 w-100" style="border-collapse: separate;">
                            <tbody>
                                <tr>
                                    <th class="align-top px-3 w-50" style="border-right: 0.5px solid;"><small>Before</small></th>
                                    <th class="align-top px-3 w-50"><small>After</small></th>
                                </tr>
                                <tr>
                                    <td class="align-top px-3" style="border-right: 0.5px solid;">
                                        <h3>{!! getBadge($summCheckJar->log_audit_result) !!}</h3>
                                    </td>
                                    <td class="align-top px-3">
                                        <h3>{!! getBadge($summCheckJar->log_audit_result_assesor) !!}</h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header text-center p-0">
                        <h6 class="fw-bold mt-2">Mandatory Item</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="mb-0 w-100" style="border-collapse: separate;">
                            <tbody>
                                <tr>
                                    <th class="align-top px-3 w-50" style="border-right: 0.5px solid;"><small>Before</small></th>
                                    <th class="align-top px-3 w-50"><small>After</small></th>
                                </tr>
                                <tr>
                                    <td class="align-top px-3" style="border-right: 0.5px solid;">
                                        <h3>{!! getBadge($summCheckJar->log_mandatory_item) !!}</h3>
                                    </td>
                                    <td class="align-top px-3">
                                        <h3>{!! getBadge($summCheckJar->log_mandatory_item_assesor) !!}</h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header text-center p-0">
                        <h6 class="fw-bold mt-2">Result Final</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="mb-0 w-100" style="border-collapse: separate;">
                            <tbody>
                                <tr>
                                    <th class="align-top px-3 w-50" style="border-right: 0.5px solid;"><small>Before</small></th>
                                    <th class="align-top px-3 w-50"><small>After</small></th>
                                </tr>
                                <tr>
                                    <td class="align-top px-3" style="border-right: 0.5px solid;">
                                        <h3>{!! getBadge($summCheckJar->log_result_final) !!}</h3>
                                    </td>
                                    <td class="align-top px-3">
                                        <h3>{!! getBadge($summCheckJar->log_result_final_assesor) !!}</h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>

            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <td colspan="2" class="align-top fw-bold">Summary Note Result</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-top fw-bold" style="white-space: nowrap; width: 1%;">Assessor</td>
                            <td class="align-top">
                                <div class="collapsible-text short" id="noteSumAssesor">
                                    {!! $summCheckJar->log_sum_note_assesor ?? '-' !!}
                                </div>
                                <a href="javascript:void(0);" class="text-primary small toggle-notes" data-target="#noteSumAssesor">View More</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-top fw-bold" style="white-space: nowrap; width: 1%;">PIC NOS MD</td>
                            <td class="align-top">
                                {!! $summCheckJar->log_note_pic ?? '-' !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center">
            <p>- No Data Available -</p>
        </div>
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
</div>