
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
