<!DOCTYPE html>
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
</html>