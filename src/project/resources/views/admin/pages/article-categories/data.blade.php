@forelse($articleCategories as $key => $category)
    <tr>
        <td>
            <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input form-check-input-single widget-9-check w-15px h-15px" type="checkbox" value="{{ $category->id }}"/>
            </div>
        </td>
        <td>
            {{ ($loop->index + 1) }}
        </td>
        <td class="align-left text-start">{{ $category->title }}</td>
        <td>
            <span @can('admin.article.categories.status') onclick="statusArticleCategory('{{ route('admin.article.categories.status', ['articleCategory' => $category->id]) }}')" @endcan >
                @include('admin.theme.elements.icons.check', ['status' => $category->comment])
            </span>
        </td>
        <td>{{ $category->parent ? $category->parent->title : 'ندارد' }}</td>
        <td>{{ $category->childs->count() }}</td>
        <td>{{ $category->created_at }}</td>
        <td>
            <button onclick="$('#modal-details-{{ $category->id }}').modal('show')" class="btn btn-sm btn-light-facebook btn-block p-2 fs-8">نمایش جزئیات</button>
            <div class="modal fade" tabindex="-1" id="modal-details-{{ $category->id }}">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">مشاهده جزئیات دسته بندی</h5>

                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                <span class="svg-icon svg-icon-2x"></span>
                            </div>
                            <!--end::Close-->
                        </div>

                        <div class="modal-body">
                            <div class="mb-6">
                                <label class="form-lable">عنوان : </label><span>{{ $category->title }}</span>
                            </div>
                            <div class="mb-6">
                                <label class="form-lable">زبان : </label><span>{{ $category->lang }}</span>
                            </div>
                            <div class="mb-6">
                                <label class="form-lable">توضیحات : </label><span>{{ $category->content }}</span>
                            </div>
                            <div class="mb-6">
                                <label class="form-lable">ارسال کامنت: </label>
                                @include('admin.theme.elements.icons.check', ['status' => $category->comment])
                            </div>
                            <div class="mb-6">
                                <label class="form-lable">سر دسته: </label>
                                <span>{{ $category->parent->title ?? 'ندارد' }}</span>

                            </div>
                            <div class="mb-6">
                                <label class="form-lable">تاریخ ایجاد: </label><span>{{ $category->created_at }}</span>
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
                    'route' => route('admin.article.categories.edit', ['articleCategory' => $category->id]),
                    'access' => 'admin.article.categories.edit'
                ])
                @include('admin.theme.elements.buttons.destroy', [
                    'route' => route('admin.article.categories.destroy', ['articleCategory' => $category->id]),
                    'access' => 'admin.article.categories.destroy',
                ])
            </div>
        </td>
    </tr>
@empty
@endforelse
