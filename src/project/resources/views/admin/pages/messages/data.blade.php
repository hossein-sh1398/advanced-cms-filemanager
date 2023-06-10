@forelse($messages as $key => $message)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox"
                       value="{{ $message->id }}"/>
            </div>
        </td>
        <td>
            {{ ($loop->index + 1) }}
        </td>
        <td>{{ $message->name }}</td>
        <td>
            <a href="#" @if (! $message->read && ! $message->deleted_at)  @can('admin.messages.edit.status') onclick ="editStatusMessage('{{route('admin.messages.edit.status', ['message' => $message->id])}}')" @endcan @endif>
                @include('admin.theme.elements.icons.check', ['status' => $message->read])
            </a>
        </td>
        <td>{{ $message->mobile }}</td>
        <td>
            @if (\Illuminate\Support\Str::length($message->content) > 30)
                {{ \Illuminate\Support\Str::substr($message->content, 0, 30) }}...
            @else
                {{ $message->content }}
            @endif
        </td>
        <td>{{ $message->created_at }}</td>
        <td>
            <button data-url="{{ route('admin.messages.show', ['message' => $message->id]) }}" class="btn btn-sm btn-light-facebook btn-block p-2 fs-8 show-details-message">نمایش جزئیات</button>
        </td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">

                @if($message->trashed())
                    @include('admin.theme.elements.buttons.restore', [
                        'route' => route('admin.messages.restore', ['id' => $message->id]),
                        'access' => 'admin.messages.restore',
                    ])
                @else
                    @include('admin.theme.elements.buttons.destroy', [
                        'route' => route('admin.messages.destroy', ['message' => $message->id]),
                        'access' => 'admin.messages.destroy',
                    ])
                @endif
            </div>
        </td>
    </tr>
@empty

@endforelse
