<form method="POST" action="{{ route('roles.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Role Name">

    <h4>Assign Permissions</h4>
    @foreach($permissions as $permission)
        <label>
            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}">
            {{ $permission->name }}
        </label><br>
    @endforeach

    <button type="submit">Save Role</button>
</form>
