@forelse($permissions as $key => $permission)
    <tr>
        <td>
            @if (! $permission->global)
                <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $permission->id }}"/>
                </div>
            @endif
        </td>
        <td>
            {{ ($loop->index + 1) }}
        </td>
        <td>{{ $permission->name }}</td>
        <td>
            <span class="badge badge-light-primary fs-7 m-1">
                {{ $permission->group }}
            </span>
        </td>
        <td>
            @if($permission->global)
             <span class="badge badge-light-primary fs-7 m-1">
                سیستمی
             </span>
            @else
                <span class="badge badge-light-info fs-7 m-1">
                دستی
             </span>
            @endif
        </td>
        <td>{{ $permission->created_at }}</td>
        <td>
            @if (! $permission->global)
                <div class="d-flex justify-content-end flex-shrink-0">
                    @include('admin.theme.elements.buttons.edit', [
                        'route' => route('admin.permissions.edit', ['permission' => $permission->id]),
                        'access' => 'admin.permissions.edit'
                    ])
                    @include('admin.theme.elements.buttons.destroy', [
                        'route' => route('admin.permissions.destroy', ['permission' => $permission->id]),
                        'access' => 'admin.permissions.destroy',
                    ])
                </div>
            @endif
        </td>
    </tr>
@empty
@endforelse
