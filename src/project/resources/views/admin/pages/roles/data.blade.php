@forelse($roles as $key => $role)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $role->id }}"/>
            </div>
        </td>
        <td>
            {{ ($loop->index + 1) }}
        </td>
        <td>{{ $role->name }}</td>
        <td>{{ $role->persian_name }}</td>
        <td>{{ $role->created_at }}</td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @if ($role->name != 'superadmin')
                    @include('admin.theme.elements.buttons.edit', [
                        'route' => route('admin.roles.edit', ['role' => $role->id]),
                        'access' => 'admin.roles.edit'
                    ])
                    @include('admin.theme.elements.buttons.destroy', [
                        'route' => route('admin.roles.destroy', ['role' => $role->id]),
                        'access' => 'admin.roles.destroy',
                    ])
                @endif
            </div>
        </td>
    </tr>
@empty

@endforelse
