<div class="card card-xl-stretch shadow-sm mb-5 mb-xl-8">
    <div class="card-body py-6">
        <div class="row">
            <div class="col-6">
                <div class="input-group">
                    @if ($array['isPaginate'] ?? false)
                        <div><label class="col-form-label">نمایش محتویات</label></div>
                        <div class="align-self-center ms-2">
                            <select id="page-length" class="form-select form-select-sm form-select-solid" data-hide-search="true" data-control="select2" data-placeholder="Select an option">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    @endif
                    <div class="align-self-center">
                        @if ($array['isSearch'] ?? false)
                            <label for="search">
                                <input class="form-control form-control-sm mx-3" name="search" type="text" id="search" placeholder="جستجو ...">
                            </label>
                        @endif
                    </div>
                    {{ $file_manager_breadcrumbs ?? '' }}
                </div>
                @if ($array['isSearch'] ?? false)
                    <input type="hidden" class="query" name="query" id="hidden_search" value="">
                @endif
                @if ($array['isPaginate'] ?? false)
                    <input type="hidden" class="query" name="page" id="hidden_page" value="1">
                    <input type="hidden" class="query" name="size" id="hidden_page_size" value="{{ $array['pageSize'] ?? 10 }}">
                @endif
                <input type="hidden" class="query" name="sort" id="hidden_column_name" value="{{ $array['columenSort'] ?? 'id'}}">
                <input type="hidden" class="query" name="dir" id="hidden_sort_type" value="{{ $array['sortDirection'] ?? 'desc' }}">
                <input type="hidden" id="hidden_url" value="{{ $url }}">
            </div>
            <div class="col-6 text-end align-self-center d-flex justify-content-end gap-3">
                @if ($array['isMore'] ?? false)
                    <div class="me-0 d-none" id="more-actions">
                        <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                            <i class="bi bi-three-dots fs-3"></i>
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                            @can("admin.$model.multiple.destroy")
                                <div class="menu-item px-3">
                                    <a href="#" title="حذف" class="menu-link px-3 d-flex justify-content-between" onclick="deleteRows(event, '{{ route("admin.$model.multiple.destroy") }}')">
                                        حذف
                                        <span class="svg-icon svg-icon-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor"/>
                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor"/>
                                                <path opacity="0.5"  d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor"/>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            @endcan
                            {{ $more_actions ?? '' }}
                        </div>
                    </div>
                @endif
                @if ($array['isExcel'] ?? false)
                    @include('admin.theme.elements.buttons.excel', ['access' => "admin.$model.export"])
                @endif
                @if ($array['isCsv'] ?? false)
                    @include('admin.theme.elements.buttons.csv', ['access' => "admin.$model.export"])
                @endif
                @if ($array['isPdf'] ?? false)
                    @include('admin.theme.elements.buttons.pdf', ['access' => "admin.$model.export"])
                @endif
            </div>
        </div>

        <div class="table-responsive mt-5">
            <table class="table table-striped table-hover align-middle text-center gs-4 gy-2 gx-2">
                {{ $slot }}
                <tbody></tbody>
            </table>
        </div>
        <div class="row pt-6" id="links"></div>
    </div>
</div>
