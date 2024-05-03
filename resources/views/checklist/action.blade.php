<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="{{ route('checklist.info', encrypt($data->id)) }}"><span class="mdi mdi-information"></span> | Info</a></li>
        <li><a class="dropdown-item drpdwn" href="{{ route('checklist.edit', encrypt($data->id)) }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
        <li><a class="dropdown-item drpdwn" href="{{ route('checklist.mark', encrypt($data->id)) }}"><span class="mdi mdi-file-eye"></span> | Mark</a></li>
    </ul>
</div>

