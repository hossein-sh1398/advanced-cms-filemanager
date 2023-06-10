@forelse($list as $key => $config)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $config->id }}"/>
            </div>
        </td>
        <td>{{ ($loop->index + 1) }}</td>
        <td>{{ $config->key }}</td>
        <td>{{ $config->created_at }}</td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @include('admin.theme.elements.buttons.edit', [
                    'route' => route('admin.configs.edit',  ['config' => $config->id]),
                    'access' => 'admin.configs.edit'
                ])
                @include('admin.theme.elements.buttons.destroy', [
                    'route' => route('admin.configs.destroy',  ['config' => $config->id]),
                    'access' => 'admin.configs.destroy',
                ])
            </div>
        </td>
    </tr>
@empty
@endforelse
