{{-- <!DOCTYPE html>
<html>
<body>
    <span>
        Dear PIC Dealers / Initiator Period Checklist,
        <br> The Period Checklist You Have Initiated Has a Start Date <b>Today</b> & You Have Not Submit it!
        <br> Detail Period Checklist as below details
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
                    <span><b>Duration Date</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $periodinfo->start_date }} <b> Until </b> {{ $periodinfo->end_date }}
                    </span>
                </td>
            </tr>
        </table>

        <br> Please Give Action For This Period
        
        <br>
        <br> [Dashboard AUDIT NOS] <br>

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
        PIC Dealer / Initiator Periode Checklist yang terhormat,
        <span class="en">Dear PIC Dealers / Initiator Period Checklist,</span>
    </p>
    <p style="margin:0 0 8px 0;">
        Daftar Periode Checklist yang telah anda inisiasi memiliki tanggal mulai <b>hari ini</b> & Anda belum mengirimkannya!
        <span class="en">The Period Checklist you have initiated has a start date <b>today</b> & you have not submit it!</span>
    </p>
    <p style="margin:0 0 8px 0;">
        Dengan detail sebagai berikut:
        <span class="en">As per the details below:</span>
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

    <p style="margin:0 0 8px 0;">
        Mohon berikan tindakan
        <span class="en">Please give action</span>
    </p>

    <br>

    <p>
        [Dashboard AUDIT NOS]
    </p>
</body>
</html>
