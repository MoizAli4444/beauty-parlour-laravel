<div class="d-flex" role="group">
    <a href="{{ route('services.edit', $service->slug) }}" class="btn btn-sm btn-outline-warning me-2">
        <i class="fas fa-edit"></i> Edit
    </a>

    <a href="{{ route('services.show', $service->slug) }}" class="btn btn-sm btn-outline-info me-2">
        <i class="fas fa-eye"></i> View
    </a>

    <form action="{{ route('services.destroy', $service->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
            <i class="fas fa-trash"></i> Delete
        </button>
    </form>

    
</div>
