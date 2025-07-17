<div class="d-flex flex-column gap-2 align-items-center">
    @php
        $role = Auth::user()->role;
        $userId = Auth::id();
        $status = $data->status;
        $detailUrl = route('listassigned.periodDetail', encrypt($data->id));
        $showAudit = in_array($status, [1, 2]) && (is_null($data->idAuditor) || $userId == $data->idAuditor);
        $showReviewAssessor = $status == 3 && (is_null($data->idAssesor) || $userId == $data->idAssesor);
        $showReviewNosMd = $status == 4;
    @endphp

    @if(in_array($role, ['Super Admin', 'Admin', 'PIC Dealers']))
        <a href="{{ $detailUrl }}" class="btn btn-sm btn-info waves-effect btn-label waves-light">
            <i class="mdi mdi-information-outline label-icon"></i> Detail
        </a>
    @elseif($role === 'Internal Auditor Dealer')
        <a href="{{ $detailUrl }}" class="btn btn-sm {{ $showAudit ? 'btn-primary' : 'btn-info' }} waves-effect btn-label waves-light">
            <i class="mdi {{ $showAudit ? 'mdi-clipboard-check' : 'mdi-information-outline' }} label-icon"></i>
            {{ $showAudit ? 'Audit Checklist' : 'Detail' }}
        </a>
    @elseif($role === 'Assessor Main Dealer')
        <a href="{{ $detailUrl }}" class="btn btn-sm {{ $showReviewAssessor ? 'btn-primary' : 'btn-info' }} waves-effect btn-label waves-light">
            <i class="mdi {{ $showReviewAssessor ? 'mdi-message-draw' : 'mdi-information-outline' }} label-icon"></i>
            {{ $showReviewAssessor ? 'Review' : 'Detail' }}
        </a>
    @elseif($role === 'PIC NOS MD')
        <a href="{{ $detailUrl }}" class="btn btn-sm {{ $showReviewNosMd ? 'btn-primary' : 'btn-info' }} waves-effect btn-label waves-light">
            <i class="mdi {{ $showReviewNosMd ? 'mdi-message-draw' : 'mdi-information-outline' }} label-icon"></i>
            {{ $showReviewNosMd ? 'Review' : 'Detail' }}
        </a>
    @endif

    @if(in_array($role, ['Super Admin', 'Admin', 'PIC NOS MD']) && in_array($status, [5]))
        <a href="{{ route('export.period', encrypt($data->id)) }}" 
            type="button" 
            class="btn btn-success waves-effect btn-label waves-light btn-sm exportBtn" 
            id="exportBtn{{ $data->id }}">
            <i class="mdi mdi-file-excel label-icon"></i>Export
        </a>
        <script>
            $(document).ready(function () {
                $('.exportBtn').click(function (e) {
                    var button = this;
                    button.disabled = true;
                    button.classList.remove("waves-effect", "btn-label", "waves-light");
                    button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Please wait...';
                    setTimeout(function () {
                        button.innerHTML = '<i class="mdi mdi-file-excel label-icon"></i>Export';
                        button.classList.add("waves-effect", "btn-label", "waves-light");
                        button.disabled = false;
                    }, 3000);
                });
            });
        </script>
    @endif
</div>