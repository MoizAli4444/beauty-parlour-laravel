<div class="d-flex" role="group">

    {!! $booking->edit_button !!}
    {!! $booking->view_button !!}
    {{-- {!! $booking->delete_button !!} --}}
    <!-- Change Status Button -->
    <button class="btn btn-sm btn-primary change-status-btn" data-id="{{ $booking->id }}"
        data-status="{{ $booking->status }}">
        Change Status
    </button>

</div>
