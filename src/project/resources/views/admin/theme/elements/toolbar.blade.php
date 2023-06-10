@if(isset($type))
    @if (Route::currentRouteName() !== 'admin.messages.create')
        @include('admin.theme.elements.buttons.save', ['access' => $access])
    @endif
    @if (! in_array(Route::currentRouteName(), ['admin.roles.access', 'admin.configs.mains', 'admin.profile.index']))
        <button type="button" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8"
                onclick="save('close')">
            <span class="align-text-bottom mx-1">ذخیره و بستن</span>
            <i class="fas fa-save fs-7"></i>
        </button>
        <button type="button" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8"
                onclick="save('create')">
            <span class="align-text-bottom mx-1">ذخیره و جدید</span>
            <i class="fas fa-save fs-7"></i>
        </button>
    @endif
    @if ($type !== 'noreturn')
        <a href="{{ $route ?? '#' }}" class="btn btn-sm btn-primary btn-block btn-hover-rise p-2 fs-8">
            <span class="align-text-bottom mx-1">بازگشت</span>
            <i class="fas fa-arrow-left fs-7"></i>
        </a>
    @endif
@else
    @include('admin.theme.elements.buttons.create', [
        'route' => $route,
        'access' => $access,
    ])

@endif
@push('admin-js')
    <script>
        function save(type) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.id = 'save_type';
            input.name = 'save_type';
            input.value = type;
            $('#create_form').append(input);

            $('#create_form').submit();
        }
    </script>
@endpush
