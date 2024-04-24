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
                        <b>{{ $periodinfo->dealer_name }}</b> ({{ $periodinfo->type }})
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
                        {{ $periodinfo->count }}
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

        <br> Thank you.
        <br> Regards,
        <br>
        <br>
        <br> {{ $emailsubmitter }}
        <br> [Person In Charge Assessor] <br>

    </span>
</body>
</html>