<a href="{{ route('parentchecklist.detail', encrypt($data->id)) }}"
    type="button" class="btn btn-sm btn-primary waves-effect btn-label waves-light">
    <i class="mdi mdi-cog label-icon"></i> Manage
</a>

{{-- <div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="{{ route('parentchecklist.info', encrypt($data->id)) }}"><span class="mdi mdi-information"></span> | Info</a></li>
        <li><a class="dropdown-item drpdwn" href="{{ route('parentchecklist.edit', encrypt($data->id)) }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
    </ul>
</div> --}}

