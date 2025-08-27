<div class="text-end mb-3">
    @if (!empty($statusUrl))
        <button type="button" class="btn btn-success btn-sm btn-bulk-actions" data-url="{{ $statusUrl }}"
            data-action="approved" data-message="Are you sure you want to approve selected {{ $itemType ?? 'items' }}?">
            Mark as Approved
        </button>

        <button type="button" class="btn btn-danger btn-sm btn-bulk-actions" data-url="{{ $statusUrl }}"
            data-action="rejected" data-message="Are you sure you want to reject selected {{ $itemType ?? 'items' }}?">
            Mark as Rejected
        </button>
    @endif
</div>
