<a href="javascript:void(0)" 
    class="btn btn-secondary btn-sm openAjaxModal"
    data-id="dif_{{ $data->id }}}" 
    data-size="xl" 
    data-url="{{ route('listassigned.diffDetail', encrypt($data->id)) }}">
    ...
</a>