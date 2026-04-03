<div class="modal-header">
    <h5 class="modal-title" id="staticBackdropLabel">Difference Detail Preview Auditor Vs. Assessor</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body md-body-scroll">
    @if($datas->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-bordered dt-init">
                <thead>
                    <tr>
                        <th class="text-center">Poin</th>
                        <th class="text-center">Response Auditor</th>
                        <th class="text-center">Assessor Correction</th>
                        <th class="text-center">Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas as $item)
                        <tr>
                            <td class="align-top">
                                <strong>{{ $item->parent_point_checklist ?? '-' }}</strong><br>
                                <small>
                                    Child Point: {{ $item->child_point_checklist ?? '-' }}<br>
                                    Sub Point: {{ $item->sub_point_checklist ?? '-' }}
                                </small>
                            </td>
                            <td class="align-top">
                                {{ $item->response ?? '-' }}
                                @if($item->path_input_response)
                                <br>
                                <a href="{{ Storage::disk('s3')->temporaryUrl($item->path_input_response, now()->addMinutes(60)) }}" type="button" class="btn btn-sm btn-info waves-effect btn-label waves-light" target="_blank">
                                    <i class="mdi mdi-eye label-icon"></i> Show
                                </a>
                                @endif
                            </td>
                            <td class="align-top">
                                {{ $item->response_correction ?? '-' }}
                            </td>
                            <td>
                                {{ $item->note_assesor ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info mb-0 text-center">
            No data available.
        </div>
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
</div>