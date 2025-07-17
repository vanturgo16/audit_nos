{{-- <!DOCTYPE html>
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
</html> --}}


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
        }
        .en {
            display: block;
            font-size: 12px;
            font-style: italic;
            margin: 2px 0 8px 0;
            line-height: 1.2;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 4px 8px;
            vertical-align: top;
            text-align: left;
        }
        .checklist-table th,
        .checklist-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .checklist-table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <p style="margin:0 0 8px 0;">
        @if($nextStatus == 3)
            Tim Assesor yang terhormat,
            <span class="en">Dear Assesor Team,</span>
        @else
            Tim Internal Auditor & Assesor yang terhormat,
            <span class="en">Dear Internal Auditor & Assesor Team,</span>
        @endif
    </p>
    <p style="margin:0 0 8px 0;">
        Kami ingin memberitahukan Anda bahwa kami telah meninjau audit checklist seperti rincian di bawah ini:
        <span class="en">We would like to inform you that we already review checklist audit as below details:</span>
    </p>

    <table class="details-table" cellspacing="0" cellpadding="0">
        <tr>
            <td><strong>Nama Periode</strong><br><span class="en">Period Name</span></td>
            <td>:</td>
            <td>{{ $periodInfo->period }}</td>
        </tr>
        <tr>
            <td><strong>Ditugaskan Kepada</strong><br><span class="en">Assign To</span></td>
            <td>:</td>
            <td>{{ $periodInfo->dealer_name }} ({{ $periodInfo->type }})</td>
        </tr>
        <tr>
            <td><strong>Total Checklist</strong><br><span class="en">Checklist Total</span></td>
            <td>:</td>
            <td>{{ $periodInfo->totalChecklist }}</td>
        </tr>
        <tr>
            <td><strong>Keputusan</strong><br><span class="en">Decision</span></td>
            <td>:</td>
            <td>{{ $nextStatus == 3 ? 'Rejected' : 'Approved' }}</td>
        </tr>
        <tr>
            <td><strong>Catatan</strong><br><span class="en">Note</span></td>
            <td>:</td>
            <td>{!! $note == null ? '-' : $note !!}</td>
        </tr>
    </table>

    <br>

    <strong>Rincian Checklist:</strong><br>
    <span class="en">Checklist Details:</span>
    <table class="checklist-table" cellspacing="0" cellpadding="0">
        <tr>
            <th>Jenis Checklist<br><span class="en">Type Checklist</span></th>
            <th>Jumlah Checklist<br><span class="en">Amount Checklist</span></th>
            <th>Keputusan<br><span class="en">Decision</span></th>
            <th>Alasan<br><span class="en">Reason</span></th>
        </tr>
        @foreach($checklistdetail as $item)
        <tr>
            <td>{{ $item->type_checklist }}</td>
            <td>{{ $item->total_checklist }}</td>
            <td>{{ $item->last_decision_pic == 1 ? 'Rejected' : 'Approved' }}</td>
            <td>{!! $item->last_reason_pic == null ? '-' : $item->last_reason_pic !!}</td>
        </tr>
        @endforeach
    </table>

    <br>

    <p>Thank you.<br>Regards,</p>
    <p>
        {{ $emailSubmitter }}<br>
        [Person In Charge MD]
    </p>
</body>
</html>