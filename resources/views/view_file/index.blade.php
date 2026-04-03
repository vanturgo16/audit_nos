<iframe
    src="{{ Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(60)) }}"
    style="width:100%; height:100%;"
    frameborder="0">
</iframe>