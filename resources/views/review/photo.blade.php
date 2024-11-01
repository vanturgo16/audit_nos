@if($data->path_input_response)
    <div class="custom-image-container">
        <div class="card">
            <a href="#" data-bs-toggle="modal" data-bs-target="#detailRspImg{{ $data->id }}">
                <img src="{{ asset($data->path_input_response) }}" class="custom-img-thumbnail" onerror="this.onerror=null;this.src='{{ asset('assets/images/no-image.png') }}'; this.alt='Image not found';">
                <div class="custom-overlay">
                    <div class="custom-text mt-4">Lihat Gambar</div>
                </div>
            </a>
        </div>
    </div>
    {{-- Modal --}}
    <div class="modal fade" id="detailRspImg{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Photo Response</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 75vh; overflow-x:auto;">
                    <div class="row">
                        <img src="{{ asset($data->path_input_response) }}" class="custom-img-thumbnail" onerror="this.onerror=null;this.src='{{ asset('assets/images/no-image.png') }}'; this.alt='Image not found';">
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@else
-
@endif
