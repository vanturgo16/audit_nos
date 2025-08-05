@extends('layouts.master')
@section('konten')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/review.css') }}" id="app-style" />

<div class="page-content">
    <div class="container-fluid">
        <!-- Header -->
        @include('list_assigned.detail.checklist.header')

        <div class="row">
            @foreach($assignChecks as $index => $item)
                @php $renderImmediately = $index < 3; @endphp
                <div id="card-container-{{ $item->id }}">
                    @if ($renderImmediately)
                        @include('list_assigned.detail.checklist.card_item', ['item' => $item, 'index' => $index + 1,
                            'perCheck' => $perCheck, 'statusCorrection' => $checkJar->last_correction_assessor,
                            'idAssesor' => $checkJar->id_assesor
                            ])
                    @else
                        <div class="card mb-3 skeleton-card" style="min-height: 300px;">
                            <div class="card-body">
                                <div class="skeleton-header d-flex align-items-center mb-3">
                                    <div class="skeleton-circle me-3"></div>
                                    <div class="skeleton-line w-50"></div>
                                </div>
                                <div class="skeleton-line w-100 mb-2"></div>
                                <div class="skeleton-line w-75 mb-2"></div>
                                <div class="skeleton-line w-25 mb-3"></div>
                                <div class="d-flex justify-content-end">
                                    <div class="skeleton-button me-2"></div>
                                    <div class="skeleton-button"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

@if($checkJar->last_correction_assessor === null)
    <!-- Static Number Display Button -->
    <button type="button" class="btn btn-secondary rounded-circle shadow static-number-btn" title="Total Reviewed">
        <span class="static-number-text">{{ $progressReviewed }}</span>
    </button>
@endif


<script>
    $(document).ready(function () {
        // Delegated event handler for toggle-indicator and toggle-notes
        $(document).on('click', '.toggle-indicator, .toggle-notes', function (e) {
            e.preventDefault();
            const target = $(this).data('target');
            const $targetElement = $(target);
            $targetElement.toggleClass('collapsible-text short');
            const isCollapsed = $targetElement.hasClass('collapsible-text');
            $(this).text(isCollapsed ? 'View More' : 'View Less');
        });
    });
</script>
<script>
    $(document).ready(function () {
        function updateCard(id, url, data = {}) {
            const card = $('#card-' + id);
            card.html(`
                <div class="card mb-3 skeleton-card" style="min-height: 300px;">
                    <div class="card-body">
                        <div class="skeleton-header d-flex align-items-center mb-3">
                            <div class="skeleton-circle me-3"></div>
                            <div class="skeleton-line w-50"></div>
                        </div>
                        <div class="skeleton-line w-100 mb-2"></div>
                        <div class="skeleton-line w-75 mb-2"></div>
                        <div class="skeleton-line w-25 mb-3"></div>
                        <div class="d-flex justify-content-end">
                            <div class="skeleton-button me-2"></div>
                            <div class="skeleton-button"></div>
                        </div>
                    </div>
                </div>
                `);
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    ...data,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log(response);
                    $('#card-' + id).hide().replaceWith(response.html);
                    $('.static-number-text').text(response.progressReviewed);
                },
                error: function (xhr) {
                    alert('Terjadi kesalahan. Coba ulangi.');
                    location.reload();
                }
            });
        }
        // Approve
        $(document).on('click', '.approve-btn', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const index = $(this).data('index');
            updateCard(id, `/review/decision/${id}/approve`, { index: index });
        });
        // Reset
        $(document).on('click', '.reset-btn', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const index = $(this).data('index');
            updateCard(id, `/review/decision/${id}/reset`, { index: index });
        });
        // Reject
        $(document).on('click', '.confirm-reject-btn', function () {
            const id = $(this).data('id');
            const note = $(this).closest('.modal').find('.reject-note').val();
            const index = $(this).data('index');
            if (!note) {
                alert('Please input reject note');
                return;
            }
            updateCard(id, `/review/decision/${id}/reject`, { note: note, index: index });
        });
        // Correction
        $(document).on('click', '.confirm-correction-btn', function () {
            const id = $(this).data('id');
            const note = $(this).closest('.modal').find('.note-assesor').val();
            const responseCorrection = $(this).closest('.modal').find('input[name="response_correction_' + id + '"]:checked').val();
            const index = $(this).data('index');
            if (!responseCorrection) {
                alert('Please select a correction option');
                return;
            }
            updateCard(id, `/review/decision/${id}/correction`, { responseCorrection: responseCorrection, note: note, index: index });
        });
    });
</script>

<script>
    $(document).ready(function () {
        const items = @json($assignChecks->pluck('id'));
        const rendered = new Set(items.slice(0, 3)); // already rendered first 3

        function loadCard(itemId, index) {
            const container = $(`#card-container-${itemId}`);
            $.ajax({
                url: `/review/decision/${itemId}/renderCardOnly`, // using reset route for reuse to get card partial
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    index: index + 1
                },
                success: function (res) {
                    container.hide().html(res.html).fadeIn();
                },
                error: function () {
                    container.html('<div class="alert alert-danger">Failed to load card</div>');
                }
            });
        }
        function isInViewport(el) {
            const rect = el.getBoundingClientRect();
            return rect.top < window.innerHeight && rect.bottom >= 0;
        }
        function checkLazyLoad() {
            items.forEach((id, index) => {
                if (!rendered.has(id)) {
                    const el = document.getElementById(`card-container-${id}`);
                    if (el && isInViewport(el)) {
                        rendered.add(id);
                        loadCard(id, index);
                    }
                }
            });
        }
        $(window).on('scroll resize', checkLazyLoad);
        checkLazyLoad(); // initial check
    });
</script>    
        
@endsection