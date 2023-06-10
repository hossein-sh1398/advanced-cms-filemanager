@forelse($users as $key => $user)
    <tr>
        <td>
            @if (auth()->id() != $user->id)
                <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $user->id }}"/>
                </div>
            @endif
        </td>
        <td>{{ ($loop->index + 1) }}</td>
        <td>{{ $user->name }}</td>
        <td>
            <span @can('admin.users.change.status') onclick="changeStatus(event, '{{ route('admin.users.change.status', ['user' => $user->id]) }}')" @endcan>
                @include('admin.theme.elements.icons.check', ['status' => $user->account_verified_at ])
            </span>
        </td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->mobile }}</td>
        <td>{{ $user->created_at }}</td>
        <td>
            <button onclick="$('#modal-details-{{ $user->id }}').modal('show')" class="btn btn-sm btn-light-facebook btn-block p-2 fs-8">نمایش جزئیات</button>
            <div class="modal fade" tabindex="-1" id="modal-details-{{ $user->id }}">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">مشاهده جزئیات کاربر</h5>

                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                <span class="svg-icon svg-icon-2x"></span>
                            </div>
                            <!--end::Close-->
                        </div>

                        <div class="modal-body">
                            <div class="mb-6">
                                <label>نام : </label><span>{{ $user->name }}</span>
                            </div>
                            <div class="mb-6">
                                <label>ایمیل : </label><span>{{ $user->email }}</span>
                            </div>
                            <div class="mb-6">
                                <label>شماره موبایل : </label><span>{{ $user->mobile }}</span>
                            </div>
                            <div class="mb-6">
                                <label>تایید اکانت: </label>
                                @include('admin.theme.elements.icons.check', ['status' => $user->account_verified_at ])
                            </div>
                            <div class="mb-6">
                                <label>تایید موبایل: </label>
                                @include('admin.theme.elements.icons.check', ['status' => $user->mobile_verified_at ])
                            </div>
                            <div class="mb-6">
                                <label>تایید ایمیل: </label>
                                @include('admin.theme.elements.icons.check', ['status' => $user->email_verified_at ])
                            </div>
                            <div class="mb-6">
                                <label>تاریخ ایجاد: </label><span>{{ $user->created_at }}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td>
            <div class="d-flex justify-content-end flex-shrink-0">
                @include('admin.theme.elements.buttons.edit', [
                    'route' => route('admin.users.edit', ['user' => $user->id]),
                    'access' => 'admin.users.edit'
                ])
                @if (auth()->id() != $user->id)
                    @include('admin.theme.elements.buttons.destroy', [
                        'route' => route('admin.users.destroy', ['user' => $user->id]),
                        'access' => 'admin.users.destroy',
                    ])
                @else
                    <span class="btn btn-icon btn-active-color-danger btn-sm cursor-default"></span>
                @endif
            </div>
        </td>
    </tr>
@empty

@endforelse
