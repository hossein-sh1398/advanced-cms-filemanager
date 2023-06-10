@forelse($comments as $key => $comment)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $comment->id }}"/>
            </div>
        </td>
        <td>
            {{ ($loop->index + 1) }}
        </td>
        <td>
            <span @if (!$comment->active) @can('admin.comments.verify') onclick="verifyComment('{{ route('admin.comments.verify', ['comment' => $comment->id]) }}')" @endcan @endif>
                @include('admin.theme.elements.icons.check', ['status' => $comment->active])
            </span>
        </td>
        <td>
            <span @if (!$comment->read) @can('admin.comments.read.status') onclick="verifyComment('{{ route('admin.comments.read.status', ['comment' => $comment->id]) }}')" @endcan @endif>
                @include('admin.theme.elements.icons.check', ['status' => $comment->read])
            </span>
        </td>
        <td>
            {{ $comment->content }}
        </td>
        <td>{{ $comment->user->name }}</td>
        <td>{{ $comment->created_at }}</td>
        <td>
            <button id="btn-show-comments-{{ $comment->id }}" class="btn btn-sm btn-light-facebook btn-block p-2 fs-8" onclick="showComment('{{ route('admin.comments.show', ['comment' => $comment->id]) }}')" >مشاهده</button>
        </td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @include('admin.theme.elements.buttons.destroy', [
                    'route' => route('admin.comments.destroy', ['comment' => $comment->id]),
                    'access' => 'admin.comments.destroy',
                ])
            </div>
        </td>
    </tr>
@empty
@endforelse
