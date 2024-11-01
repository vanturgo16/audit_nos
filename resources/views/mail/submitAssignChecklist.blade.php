<!DOCTYPE html>
<html>
<body>
    <span>
        Dear Internal Auditor Team,
        <br> We Would Like to Inform you that We Already Assign Checklist Audit.
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
            @foreach($groupTypeChecks as $item)
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span>{{ $item->type_checklist }}</span>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <span>{{ $item->countCheck }}</span>
                </td>
            </tr>
            @endforeach
        </table>

        <br> Thank you.
        <br> Regards,
        <br>
        <br>
        <br> {{ $emailSubmitter }}
        <br> [Person In Charge Dealer] <br>

    </span>
</body>
</html>