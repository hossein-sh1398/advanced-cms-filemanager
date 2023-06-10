@forelse($newsLetters as $key => $newsLetter)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $newsLetter->id }}"/>
            </div>
        </td>
        <td>
            {{ ($loop->index + 1) }}
        </td>
        <td>{{ $newsLetter->email }}</td>
        <td>
            @if ($newsLetter->email)
                <a href="#" @can('admin.news.letters.verify.email') @if (! $newsLetter->active_email && ! $newsLetter->deleted_at) onclick ="verifyContactNewsLetters('{{route('admin.news.letters.verify.email', ['newsLetter' => $newsLetter->id])}}')" @endif @endcan>
                    @include('admin.theme.elements.icons.check', ['status' => $newsLetter->active_email])
                </a>
            @else
                -
            @endif
        </td>
        <td>
            @if ($newsLetter->mobile)
                <a href="#" @can('admin.news.letters.verify.mobile') @if (! $newsLetter->active_mobile && ! $newsLetter->deleted_at) onclick ="verifyContactNewsLetters('{{route('admin.news.letters.verify.mobile', ['newsLetter' => $newsLetter->id])}}')" @endif @endcan>
                    @include('admin.theme.elements.icons.check', ['status' => $newsLetter->active_mobile])
                </a>
            @else
                -
            @endif
        </td>
        <td>{{ $newsLetter->mobile }}</td>
        <td>{{ $newsLetter->created_at }}</td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @include('admin.theme.elements.buttons.edit', [
                    'route' => route('admin.news.letters.edit', ['newsLetter' => $newsLetter->id]),
                    'access' => 'admin.news.letters.edit'
                ])
                @include('admin.theme.elements.buttons.destroy', [
                    'route' => route('admin.news.letters.destroy', ['newsLetter' => $newsLetter->id]),
                    'access' => 'admin.news.letters.destroy',
                ])
            </div>
        </td>
    </tr>
@empty

@endforelse
