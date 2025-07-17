<div class="d-flex" role="group">
    {{-- <a href="{{ route('services.edit', $service->slug) }}" class="btn btn-sm btn-outline-warning me-2">
        <i class="fas fa-edit"></i> Edit
    </a>

    <a href="{{ route('services.show', $service->slug) }}" class="btn btn-sm btn-outline-info me-2">
        <i class="fas fa-eye"></i> View
    </a> --}}

    {!! $service->edit_button !!}
    {!! $service->view_button !!}
    {!! $service->delete_button !!}


    {{-- {!! render_delete_button($service->id, route('services.destroy', $service->id)) !!} --}}


</div>
