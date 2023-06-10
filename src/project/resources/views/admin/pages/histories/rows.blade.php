@forelse($histories as $key => $history)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $history->id }}"/>
            </div>
        </td>
        <td>
            {{ ($loop->index + 1) }}
        </td>
        <td>{{ $history->user->name}}</td>
        <td>{{ $history->action}}</td>
        <td>{{ $history->created_at }}</td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @include('admin.theme.elements.buttons.destroy', [
                    'route' => route('admin.histories.destroy', ['history' => $history->id]),
                    'access' => 'admin.histories.destroy',
                ])
            </div>
        </td>
    </tr>
@empty
@endforelse
