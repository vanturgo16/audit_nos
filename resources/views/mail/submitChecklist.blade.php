<!DOCTYPE html>
<html>
<body>
    <span>
        Dear Assessor Team,
        <br> We Would Like to Inform you that We Already Submit Checklist Audit.
        <br> Please Kindly check as below details
        <br>
        <br>

        <table cellspacing="0" cellpadding="0">
            @foreach($test as $t)
            <tr>
                <td>
                    <span><b>{{ $t->name }}</b></span>
                </td>
                <td>
                    <span>	&nbsp; : 	</span>
                </td>
                <td>
                    <span>&nbsp;
                        {{ $t->email }}
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
        <br> [Person In Charge Internal Auditor] <br>

    </span>
</body>
</html>