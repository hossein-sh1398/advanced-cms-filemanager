@extends('admin.theme.master')
{{--@section('admin-title')--}}

{{--@stop--}}
@push('admin-css')
    <style>
        th, td {
            text-align: right !important;
        }
    </style>
@endpush
@section('admin-toolbar')
    @can('admin.permissions.sync')
        <a href="{{ route('admin.permissions.sync') }}" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8">
            <span class="align-text-bottom mx-1">همگام سازی</span>
            <i class="fas fa-refresh fs-7"></i>
        </a>
    @endcan
    @include('admin.theme.elements.toolbar', [
        'route' =>  route('admin.roles.index'),
        'access' => 'admin.roles.add.permissions.to.role',
        'type' => true,
    ])
@endsection
@section('admin-content')
    <div class="card card-xl-stretch shadow-sm mb-5 mb-xl-8">
        <div class="card-body py-6">
            <div class="table-responsive mt-5">
                <table class="table table-bordered align-middle text-center gs-4 gy-2 gx-2">
                    <form action="{{ route('admin.roles.add.permissions.to.role') }}" id="create_form" method="POST">
                        @csrf
                        <thead>
                        <tr class="fw-bolder text-muted">
                            <th class="min-w-150px cursor-default">نام دسترسی</th>

                            @foreach ($roles as $role)
                                <th class="min-w-150px">
                                    <div class="form-check form-check-custom form-check-solid form-check-sm">
                                        <input class="form-check-input checkAll w-15px h-15px" type="checkbox" @checked($role->permissions->count())
                                                id="{{ $role->name }}"/>
                                        <label class="form-check-label cursor-pointer" for="{{ $role->name }}">
                                            <span class="cursor-pointer">{{ ucfirst($role->name) }}</span>
                                        </label>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($permissions as $groupName => $groupPermissions)
                            <tr class="bg-primary bg-opacity-15 bg-hover-opacity-15">
                                <td>{{ ucfirst($groupName) }}</td>
                                @foreach ($roles as $role)
                                    <td>
                                        <div class="form-check form-check-custom form-check-solid form-check-sm">
                                            <input type="checkbox" id="{{ $groupName }}" class="form-check-input check-group w-15px h-15px cursor-pointer {{ $role->name }}" @checked($role->permissions->where('group', $groupName)->count())
                                                   data-group_name="{{ $groupName . '.' . $role->name }}">
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                            @foreach ($groupPermissions as $permission)
                                <tr class="bg-hover-secondary text-hover-inverse-secondary cursor-default">
                                    <td>
                                        {{ $permission->name }}
                                    </td>
                                    @foreach ($roles as $role)
                                        <td>
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input type="checkbox" name="role[{{ $role->name }}][]"
                                                       id="{{ $permission->name.'-'.$role->name }}"
                                                       value="{{ $permission->name }}"
                                                       class="form-check-input {{ $groupName }} {{ $role->name }} w-15px h-15px cursor-pointer"
                                                        {{ $rolesPermissions[$role->name][$permission->name] ? 'checked' : '' }}
                                                >
                                            </div>
                                        </td>
                                    @endforeach

                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </form>
                </table>
            </div>
        </div>
    </div>
@stop

@push('admin-js')
    @include('admin.theme.errors')
    <script>
        $(".checkAll").click(function () {
            $('.' + this.id).prop('checked', this.checked);
        });

        $(".check-group").click(function () {
            $('.' + $(this).data('group_name')).prop('checked', this.checked);
        });
    </script>
    </script>
@endpush

