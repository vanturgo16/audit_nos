{{-- <!DOCTYPE html>
<html>
<body>
    <span>
        Dear Internal Auditor Team,
        <br> We Would Like to Inform you that We Have Already Extend Period in This Checklist.
        <br> Please Kindly Give Action For Checklist as below details
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
                        {{ $periodInfo->period }}
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
                        {{ $periodInfo->dealer_name }} ({{ $periodInfo->type }})
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
                        {{ $periodInfo->totalChecklist }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><b>Duration Date</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $periodInfo->start_date }} <b> Until </b> {{ $periodInfo->end_date }}
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
            </tr>
            @foreach($checklistDetail as $item)
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span>{{ $item->type_checklist }}</span>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span>{{ $item->total_checklist }}</span>
                </td>
            </tr>
            @endforeach
        </table>

        <br> Thank you.
        <br> Regards,
        <br>
        <br>
        <br> {{ $emailSubmitter }}
        <br> [Person In Charge Dealers] <br>

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
        Tim Auditor Internal yang terhormat,
        <span class="en">Dear Internal Auditor Team,</span>
    </p>
    <p style="margin:0 0 8px 0;">
        Kami ingin memberitahukan Anda bahwa kami telah memperpanjang periode checklist.
        <span class="en">We would like to inform you that we have extended the checklist period.</span>
    </p>
    <p style="margin:0 0 8px 0;">
        Mohon untuk memberikan tindakan pada checklist dengan detail sebagai berikut:
        <span class="en">Please kindly take action for the checklist as per the details below:</span>
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
            <td><strong>Durasi Tanggal</strong><br><span class="en">Duration Date</span></td>
            <td>:</td>
            <td>
                {{ $periodInfo->start_date }} <strong>sampai</strong> {{ $periodInfo->end_date }}
            </td>
        </tr>
    </table>

    <br>

    <strong>Rincian Checklist:</strong><br>
    <span class="en">Checklist Details:</span>
    <table class="checklist-table" cellspacing="0" cellpadding="0">
        <tr>
            <th>Jenis Checklist<br><span class="en">Type Checklist</span></th>
            <th>Jumlah Checklist<br><span class="en">Amount Checklist</span></th>
        </tr>
        @foreach($checklistDetail as $item)
        <tr>
            <td>{{ $item->type_checklist }}</td>
            <td>{{ $item->total_checklist }}</td>
        </tr>
        @endforeach
    </table>

    <br>

    <p>Thank you.<br>Regards,</p>
    <p>
        {{ $emailSubmitter }}<br>
        [Person In Charge Dealer]
    </p>
</body>
</html>