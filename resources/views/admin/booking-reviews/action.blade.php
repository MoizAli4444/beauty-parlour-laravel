{{-- <div class="d-flex" role="group"> --}}
<div class="btn-group" role="group">
    {{-- <div class="d-flex gap-2 flex-nowrap"> --}}
    {!! $booking_review->edit_button !!}
    {!! $booking_review->view_button !!}
    {{-- {!! $booking_review->delete_button !!} --}}
    <!-- Change Status Button -->
    <td>{!! $booking_review->change_status_button !!}</td>


</div>
