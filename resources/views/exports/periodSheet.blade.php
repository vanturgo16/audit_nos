<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid #000;" rowspan="2">No</th>
            <th style="border: 1px solid #000;" colspan="3">Point {{ $items[0]->type_checklist }}</th>
            <th style="border: 1px solid #000;" rowspan="2">Indikator</th>
            <th style="border: 1px solid #000;" rowspan="2">Mandatory</th>
            <th style="border: 1px solid #000;" rowspan="2">Response</th>
            <th style="border: 1px solid #000;" rowspan="2">File Response</th>
        </tr>
        <tr>
            <th style="border: 1px solid #000;">Parent</th>
            <th style="border: 1px solid #000;">Child</th>
            <th style="border: 1px solid #000;">Sub</th>
        </tr>
    </thead>
    <tbody>
        @php
            $previousParentPoint = null;
            $rowspan = 1;
        @endphp

        @foreach($items as $index => $item)
            @if($item instanceof App\Models\ChecklistJaringan)
                @continue
            @endif

            @if($previousParentPoint !== $item->parent_point_checklist)
                @php
                    $rowspan = 1;
                @endphp
                <tr>
                    <td style="border: 1px solid #000;">{{ $loop->iteration }}</td>
                    <td style="border-top: 1px solid #000;" rowspan="{{ $rowspan }}">{{ $item->parent_point_checklist }}</td>
                    <td style="border: 1px solid #000;">{{ $item->child_point_checklist ?? '-' }}</td>
                    <td style="border: 1px solid #000;">{{ $item->sub_point_checklist }}</td>
                    <td style="border: 1px solid #000;">{!! $item->indikator ?? '-' !!}</td>
                    <td style="border: 1px solid #000;">
                        @if($item->ms == 1) (S) @endif
                        @if($item->mg == 1) (G) @endif
                        @if($item->mp == 1) (P) @endif
                    </td>
                    <td style="border: 1px solid #000;">{!! $item->response ?? '-' !!}</td>
                    <td style="border: 1px solid #000;">
                        @if($item->path_input_response)
                            <a href="{{ url($item->path_input_response) }}">View File</a>
                        @else 
                            -
                        @endif
                    </td>
                </tr>
            @else
                @php
                    $rowspan++;
                @endphp

                <tr>
                    <td style="border: 1px solid #000;">{{ $loop->iteration }}</td>
                    <td style="padding: 8px;"></td>
                    <td style="border: 1px solid #000;">{{ $item->child_point_checklist ?? '-' }}</td>
                    <td style="border: 1px solid #000;">{{ $item->sub_point_checklist }}</td>
                    <td style="border: 1px solid #000;">{!! $item->indikator ?? '-' !!}</td>
                    <td style="border: 1px solid #000;">
                        @if($item->ms == 1) (S) @endif
                        @if($item->mg == 1) (G) @endif
                        @if($item->mp == 1) (P) @endif
                    </td>
                    <td style="border: 1px solid #000;">{!! $item->response ?? '-' !!}</td>
                    <td style="border: 1px solid #000;">
                        @if($item->path_input_response)
                            <a href="{{ url($item->path_input_response) }}">View File</a>
                        @else 
                            -
                        @endif
                    </td>
                </tr>
            @endif

            @php
                $previousParentPoint = $item->parent_point_checklist;
            @endphp
        @endforeach
        <tr>
            <td></td>
            <td style="border-top: 1px solid #000;"></td>
        </tr>
    </tbody>
</table>

@foreach($items as $item)
    @if($item instanceof App\Models\ChecklistJaringan)
        <table style="border-collapse: collapse; width: 100%; margin-top: 20px;">
            <tbody>
                <tr>
                    <td></td>
                    <td style="border: 1px solid #000;"><b>% Result</b></td>
                    <td style="border: 1px solid #000;">{{ $item->result_percentage }}%</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="border: 1px solid #000;"><b>Result Audit</b></td>
                    <td style="border: 1px solid #000;">{{ $item->audit_result }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="border: 1px solid #000;"><b>Mandatory Item</b></td>
                    <td style="border: 1px solid #000;">{{ $item->mandatory_item }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="border: 1px solid #000;"><b>RESULT FINAL</b></td>
                    <td style="border: 1px solid #000;">{{ $item->result_final }}</td>
                </tr>
            </tbody>
        </table>
    @endif
@endforeach
