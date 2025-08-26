<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="statusForm" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Change Booking Status</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="booking_id" id="booking_id">

          <label for="status">Select New Status</label>
          <select name="status" id="status_select" class="form-select" required>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
          </select>

          <!-- Cancel / Reject Reason (hidden by default) -->
          <div class="mt-3 d-none" id="reasonBox">
              <label for="cancel_reason">Reason</label>
              <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="3"
                        placeholder="Enter reason for cancellation or rejection"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
      </div>
    </form>
  </div>
</div>
