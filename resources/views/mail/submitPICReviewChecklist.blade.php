<!DOCTYPE html>
<html>
<body>
    <span>
        @if($nextStatus == 3)
            Dear Assessor Team,
        @else
            Dear Internal Auditor & Assessor Team,
        @endif
        <br> We Would Like to Inform you that We Already Review Checklist Audit as below details
        <br>
        <br>

        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <span><b>Period Name</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $periodinfo->period }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><b>Assign To</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $periodinfo->dealer_name }} ({{ $periodinfo->type }})
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><b>Checklist Total</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $periodinfo->totalChecklist }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><b>Decision</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        @if($nextStatus == 3)
                            Rejected
                        @else
                            Approved
                        @endif
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><b>Note</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        @if($note == null)
                            -
                        @else
                            {!! $note !!}
                        @endif
                    </span>
                </td>
            </tr>
        </table>

        <br>
        <table cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 1px solid #000; text-align: center;">
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span><b>Type Checklist</b></span>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span><b>Amount Checklist</b></span>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span><b>Decision</b></span>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span><b>Reason</b></span>
                </td>
            </tr>
            @foreach($checklistdetail as $detail)
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span>{{ $detail->type_checklist }}</span>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span>{{ $detail->total_checklist }}</span>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span>
                        @if($detail->last_decision_pic == 1)
                            Rejected
                        @else
                            Approved
                        @endif
                    </span>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span>
                        @if($detail->last_reason_pic == null)
                            -
                        @else 
                            {!! $detail->last_reason_pic !!}
                        @endif
                    </span>
                </td>
            </tr>
            @endforeach
        </table>
        
        <br> Thank you.
        <br> Regards,
        <br>
        <br>
        <br> {{ $emailsubmitter }}
        <br> [Person In Charge MD] <br>

    </span>
</body>
</html>