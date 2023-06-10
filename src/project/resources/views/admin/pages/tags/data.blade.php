@forelse($tags as $key => $tag)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $tag->id }}"/>
            </div>
        </td>
        <td>
            {{ ($loop->index + 1) }}
        </td>
        <td>{{ $tag->title }}</td>
        <td>{{ $tag->created_at }}</td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @include('admin.theme.elements.buttons.edit', [
                    'route' => route('admin.tags.edit', ['tag' => $tag->id]),
                    'access' => 'admin.tags.edit'
                ])
                @include('admin.theme.elements.buttons.destroy', [
                    'route' => route('admin.tags.destroy', ['tag' => $tag->id]),
                    'access' => 'admin.tags.destroy',
                ])
            </div>
        </td>
    </tr>
@empty

@endforelse
