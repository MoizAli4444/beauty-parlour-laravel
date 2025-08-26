<div class="d-flex" role="group">

    {!! $booking_review->edit_button !!}
    {!! $booking_review->view_button !!}
    {{-- {!! $booking_review->delete_button !!} --}}
    <!-- Change Status Button -->
    <button class="btn btn-sm btn-primary change-status-btn" data-id="{{ $booking_review->id }}"
        data-status="{{ $booking_review->status }}">
        Change Status
    </button>

</div>
