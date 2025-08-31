{{-- 
<div class="text-end mb-3">
    @if (!empty($deleteUrl))
        <button id="bulkDelete" class="btn btn-danger btn-sm btn-bulk-actions" 
            data-url="{{ $deleteUrl }}"
            data-action="delete" 
            data-message="Are you sure you want to delete selected {{ $itemType ?? 'items' }}?">
            Delete Selected
        </button>
    @endif

    @if (!empty($statusUrl))
        <button id="bulkActivate" class="btn btn-success btn-sm btn-bulk-actions" 
            data-url="{{ $statusUrl }}"
            data-action="active" 
            data-message="Are you sure you want to activate selected {{ $itemType ?? 'items' }}?">
            Mark Active
        </button>

        

        <button id="bulkDeactivate" class="btn btn-warning btn-sm btn-bulk-actions" 
            data-url="{{ $statusUrl }}"
            data-action="inactive"
            data-message="Are you sure you want to deactivate selected {{ $itemType ?? 'items' }}?">
            Mark Inactive
        </button>

        
    @endif
</div> --}}


{{-- bulk-actions.blade.php --}}
<div class="text-end mb-3">
    @if (!empty($actions))
        @foreach ($actions as $action)
            <button class="btn btn-sm btn-bulk-actions {{ $action['class'] }}"
                data-url="{{ $action['url'] ?? ($statusUrl ?? '#') }}" data-action="{{ $action['value'] }}"
                data-message="Are you sure you want to {{ strtolower($action['text']) }} the selected {{ $itemType ?? 'items' }}?">
                {{ $action['text'] }}
            </button>
        @endforeach
    @endif
</div>
