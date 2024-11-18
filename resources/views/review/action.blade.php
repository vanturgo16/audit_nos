@if(in_array(Auth::user()->role, ['Assessor Main Dealer']))
    @if($data->approve === null)
        <div id="decision{{ $data->id }}">
            <a href="#" type="button" class="btn btn-sm btn-success approve-btn" title="Approve" id="appr{{ $data->id }}">
                <i class="mdi mdi-check-circle-outline label-icon"></i>
            </a>
            <a href="#" type="button" class="btn btn-sm btn-danger reject-btn" title="Reject" id="rej{{ $data->id }}">
                <i class="mdi mdi-close-circle label-icon"></i>
            </a>
        </div>
    @elseif($data->approve === 0)
        <a href="#" type="button" class="btn btn-sm btn-info reset-btn" title="Reset Decision" id="rst{{ $data->id }}">
            <i class="mdi mdi-reload label-icon"></i>
        </a>
    @elseif($data->approve === 1)
        <a href="#" type="button" class="btn btn-sm btn-info reset-btn" title="Reset Decision" id="rst{{ $data->id }}">
            <i class="mdi mdi-reload label-icon"></i>
        </a>
    @elseif(in_array($data->approve, [2,3]))
        -
    @endif

    <!-- Loading element, always present -->
    <span id="load{{ $data->id }}" class="badge bg-info text-white" style="display: none;">
        <i class="mdi mdi-loading mdi-spin label-icon"></i>
    </span>

    <script>
        $(document).ready(function() {
            var idList = '{{ $data->id }}';

            // Click event for the Approve button
            $('#appr' + idList).on('click', function(e) {
                e.preventDefault();
                handleDecision(idList, 1); // 1 for approve
            });

            // Click event for the Reject button
            $('#rej' + idList).on('click', function(e) {
                e.preventDefault();
                handleDecision(idList, 0); // 0 for reject
            });

            // Click event for the Reset button
            $('#rst' + idList).on('click', function(e) {
                e.preventDefault();
                handleDecision(idList, null); // null for reset
            });

            function handleDecision(id, decisionValue) {
                toggleLoading(id);

                // AJAX call to send data
                $.ajax({
                    url: '{{ route("review.decisionChecklist") }}',
                    type: 'POST',
                    data: {
                        id: id,
                        decision: decisionValue,
                        _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
                    },
                    success: function(response) {
                        console.log(response);
                        $("#server-side-table").DataTable().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        // Optionally revert the loading display on error
                        $('#decision' + id).show();
                        $('#rst' + id).show();
                        $('#load' + id).hide();
                    }
                });
            }

            function toggleLoading(id) {
                $('#decision' + id).hide(); // Hide the decision buttons
                $('#rst' + id).hide(); // Hide the reset button
                $('#load' + id).show(); // Show the loading icon
            }
        });
    </script>
@else 
-
@endif
