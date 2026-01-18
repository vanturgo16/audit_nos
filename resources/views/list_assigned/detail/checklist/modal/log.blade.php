<div class="modal-header">
    <h5 class="modal-title" id="staticBackdropLabel">Log History Response & Correction</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body md-body-scroll">
    <div class="table-responsive">
        <table class="table table-bordered dt-init">
            <thead>
                <tr>
                    <th colspan="3" class="text-center" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;">
                        Log Response Before
                    </th>
                    <th colspan="3" class="text-center" style="background-color: #0d6efd; color: #fff; border: 1px solid #dee2e6;">
                        Current Response
                    </th>
                </tr>
                <tr>
                    <th class="text-center" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;">Auditor</th>
                    <th class="text-center" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;">Assessor</th>
                    <th class="text-center" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;">Note Assessor</th>
                    <th class="text-center" style="background-color: #0d6efd; color: #fff; border: 1px solid #dee2e6;">Auditor</th>
                    <th class="text-center" style="background-color: #0d6efd; color: #fff; border: 1px solid #dee2e6;">Assessor</th>
                    <th class="text-center" style="background-color: #0d6efd; color: #fff; border: 1px solid #dee2e6;">Note Assessor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="align-top" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;">
                        {{ $logResponse->log_response ?? '-' }}
                    </td>
                    <td class="align-top" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;">
                        {{ $logResponse->log_response_correction ?? '-' }}
                    </td>
                    <td class="align-top" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;">
                        {{ $logResponse->log_note_assesor ?? '-' }}
                    </td>
                    <td class="align-top" style="background-color: #e7f1ff; font-weight: 600; border: 1px solid #0d6efd;">
                        {{ $logResponse->response ?? '-' }}
                    </td>
                    <td class="align-top" style="background-color: #e7f1ff; font-weight: 600; border: 1px solid #0d6efd;">
                        {{ $logResponse->response_correction ?? '-' }}
                    </td>
                    <td class="align-top" style="background-color: #e7f1ff; font-weight: 600; border: 1px solid #0d6efd;">
                        {{ $logResponse->note_assesor ?? '-' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
</div>