@extends('admin.theme.master')
{{--@section('admin-title')--}}

{{--@stop--}}

@section('admin-toolbar')
    {{-- <!--begin::Actions-->

         <!--begin::Filter menu-->
         <div class="m-0">
             <!--begin::Menu toggle-->
             <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder"
                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                 <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                 <span class="svg-icon svg-icon-5 svg-icon-gray-500 me-1">
                                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                  viewBox="0 0 24 24" fill="none">
                                                 <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                       fill="currentColor"/>
                                             </svg>
                                         </span>
                 <!--end::Svg Icon-->Filter</a>
             <!--end::Menu toggle-->
             <!--begin::Menu 1-->
             <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                  id="kt_menu_6244763d93048">
                 <!--begin::Header-->
                 <div class="px-7 py-5">
                     <div class="fs-5 text-dark fw-bolder">Filter Options</div>
                 </div>
                 <!--end::Header-->
                 <!--begin::Menu separator-->
                 <div class="separator border-gray-200"></div>
                 <!--end::Menu separator-->
                 <!--begin::Form-->
                 <div class="px-7 py-5">
                     <!--begin::Input group-->
                     <div class="mb-10">
                         <!--begin::Label-->
                         <label class="form-label fw-bold">Status:</label>
                         <!--end::Label-->
                         <!--begin::Input-->
                         <div>
                             <select class="form-select form-select-solid" data-kt-select2="true"
                                     data-placeholder="Select option" data-dropdown-parent="#kt_menu_6244763d93048"
                                     data-allow-clear="true">
                                 <option></option>
                                 <option value="1">Approved</option>
                                 <option value="2">Pending</option>
                                 <option value="2">In Process</option>
                                 <option value="2">Rejected</option>
                             </select>
                         </div>
                         <!--end::Input-->
                     </div>
                     <!--end::Input group-->
                     <!--begin::Input group-->
                     <div class="mb-10">
                         <!--begin::Label-->
                         <label class="form-label fw-bold">Member Type:</label>
                         <!--end::Label-->
                         <!--begin::Options-->
                         <div class="d-flex">
                             <!--begin::Options-->
                             <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                 <input class="form-check-input" type="checkbox" value="1"/>
                                 <span class="form-check-label">Author</span>
                             </label>
                             <!--end::Options-->
                             <!--begin::Options-->
                             <label class="form-check form-check-sm form-check-custom form-check-solid">
                                 <input class="form-check-input" type="checkbox" value="2" checked="checked"/>
                                 <span class="form-check-label">Customer</span>
                             </label>
                             <!--end::Options-->
                         </div>
                         <!--end::Options-->
                     </div>
                     <!--end::Input group-->
                     <!--begin::Input group-->
                     <div class="mb-10">
                         <!--begin::Label-->
                         <label class="form-label fw-bold">Notifications:</label>
                         <!--end::Label-->
                         <!--begin::Switch-->
                         <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                             <input class="form-check-input" type="checkbox" value="" name="notifications"
                                    checked="checked"/>
                             <label class="form-check-label">Enabled</label>
                         </div>
                         <!--end::Switch-->
                     </div>
                     <!--end::Input group-->
                     <!--begin::Actions-->
                     <div class="d-flex justify-content-end">
                         <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2"
                                 data-kt-menu-dismiss="true">Reset
                         </button>
                         <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply
                         </button>
                     </div>
                     <!--end::Actions-->
                 </div>
                 <!--end::Form-->
             </div>
             <!--end::Menu 1-->
         </div>
         <!--end::Filter menu-->
         <!--begin::Secondary button-->
         <!--end::Secondary button-->
         <!--begin::Primary button-->
         <a href="../../demo1/dist/.html" class="btn btn-sm btn-primary" data-bs-toggle="modal"
            data-bs-target="#kt_modal_create_app">Create</a>
         <!--end::Primary button-->

     <!--end::Actions-->--}}
    <a href="#" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8">
        <span class="align-text-bottom mx-1">ذخیره</span>
        <i class="fas fa-save fs-7"></i>
    </a>
    <a href="#" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8">
        <span class="align-text-bottom mx-1">ذخیره و بستن</span>
        <i class="fas fa-save fs-7"></i>
    </a>
    <a href="#" class="btn btn-sm btn-secondary btn-block btn-hover-rise p-2 fs-8">
        <span class="align-text-bottom mx-1">ذخیره و جدید</span>
        <i class="fas fa-save fs-7"></i>
    </a>
    <a href="#" class="btn btn-sm btn-primary btn-block btn-hover-rise p-2 fs-8">
        <span class="align-text-bottom mx-1">بازگشت</span>
        <i class="fas fa-arrow-left fs-7"></i>
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->

        <!--end::Svg Icon-->
    </a>

@stop
@section('admin-content')
    <div class="card card-xl-stretch shadow-sm mb-5 mb-xl-8">
        <!--begin::Body-->
        <div class="card-body py-6">
            <div class="row">
                <div class="col-6">
                    <div class="input-group">
                        <div>
                            <label class="col-form-label">نمایش محتویات</label>
                        </div>
                        <div class="align-self-center ms-2">
                            <select class="form-select form-select-sm form-select-solid" data-hide-search="true" data-control="select2" data-placeholder="Select an option">
                                <option>10</option>
                                <option>20</option>
                                <option>30</option>
                                <option>50</option>
                            </select>
                        </div>
                        <div class="align-self-center">
                            <label for="search">
                                <input class="form-control form-control-sm mx-3" type="text" id="search" placeholder="جستجو ...">
                            </label>
                        </div>
                    </div>

                </div>
                <div class="col-6 text-end align-self-center">
                    <button class="btn btn-light p-2 fs-8">
                        <span class="align-text-bottom text-info">Excel</span>
                        <i class="fas fa-file-excel fs-7 text-info"></i>
                    </button>
                    <button class="btn btn-light p-2 fs-8">
                        <span class="align-text-bottom text-info">CSV</span>
                        <i class="fas fa-file-csv fs-7 text-info"></i>
                    </button>
                    <button class="btn btn-light p-2 fs-8">
                        <span class="align-text-bottom text-info">PDF</span>
                        <i class="fas fa-file-pdf fs-7 text-info"></i>
                    </button>
                </div>
            </div>
            <!--begin::Table container-->
            <div class="table-responsive mt-5">
                <!--begin::Table-->
                <table class="table table-striped table-hover align-middle text-center gs-4 gy-2 gx-2">
                    <thead>
                    <tr class="fw-bolder text-muted">
                        <th class="w-25px">
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input w-15px h-15px" type="checkbox" value="1" data-kt-check="true"
                                       data-kt-check-target=".widget-9-check"/>
                            </div>
                        </th>
                        <th class="min-w-50px align-center">ردیف</th>
                        <th class="min-w-150px">نام کامل</th>
                        <th class="min-w-150px">وضعیت</th>
                        <th class="min-w-150px">ایمیل</th>
                        <th class="min-w-150px">موبایل</th>
                        <th class="min-w-150px table-sort-desc">تاریخ ایجاد</th>
                        <th class="min-w-150px">نمایش</th>
                        <th class="min-w-100px text-end">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for($i =1;$i<=10;$i++)
                        <tr>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input widget-9-check w-15px h-15px" type="checkbox" value="1"/>
                                </div>
                            </td>
                            <td>
                                {{ $i }}
                            </td>
                            <td class="align-left text-start">
                                {{ Faker\Provider\fa_IR\Company::companyField() }}
                            </td>
                            <td>
                                        <span class="btn btn-icon btn-color-success btn-sm">
                                            <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                            <span class="svg-icon svg-icon-4">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="currentColor"/>
<path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="currentColor"/>
</svg>

                                            </span>
                                            <!--end::Svg Icon-->
                                        </span>
                            </td>
                            <td>
                                {{ Faker\Provider\en_US\Person::firstNameMale()."@".Faker\Provider\fa_IR\Internet::freeEmailDomain() }}
                            </td>
                            <td>
                                {{ Faker\Provider\fa_IR\PhoneNumber::mobileNumber() }}
                            </td>

                            <td>
                                {{ verta()->format('j F Y ساعت H:i') }}
                            </td>
                            <td>
                                <button class="btn btn-sm btn-light-facebook btn-block p-2 fs-8">نمایش جزئیات</button>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end flex-shrink-0">
                                    <a href="#" class="btn btn-icon btn-active-color-warning btn-sm">
                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                        <span class="svg-icon svg-icon-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none">
																					<path opacity="0.3"
                                                                                          d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                                                          fill="currentColor"/>
																					<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                                                          fill="currentColor"/>
																				</svg>
                                            </span>
                                        <!--end::Svg Icon-->
                                    </a>
                                    <a href="#" class="btn btn-icon btn-active-color-danger btn-sm">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                        <span class="svg-icon svg-icon-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none">
																					<path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                                                          fill="currentColor"/>
																					<path opacity="0.5"
                                                                                          d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                                                          fill="currentColor"/>
																					<path opacity="0.5"
                                                                                          d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                                                          fill="currentColor"/>
																				</svg>
                                            </span>
                                        <!--end::Svg Icon-->
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endfor
                    </tbody>
                </table>

                <!--end::Table-->
            </div>
            <!--end::Table container-->
            <div class="row pt-6">

                <div class="col-md-6 pt-2">
                    <span>نمایش 1 تا 10 از مجموع 1,423 مورد</span>
                </div>
                <div class="col-md-6 ">
                    <ul class="pagination justify-content-end">
                        <li class="page-item previous disabled"><a href="#" class="page-link min-w-25px h-25px"><i class="previous"></i></a></li>
                        <li class="page-item "><a href="#" class="page-link min-w-25px h-25px">1</a></li>
                        <li class="page-item active"><a href="#" class="page-link min-w-25px h-25px">2</a></li>
                        <li class="page-item "><a href="#" class="page-link min-w-25px h-25px">3</a></li>
                        <li class="page-item "><a href="#" class="page-link min-w-25px h-25px">4</a></li>
                        <li class="page-item "><a href="#" class="page-link min-w-25px h-25px">5</a></li>
                        <li class="page-item "><a href="#" class="page-link min-w-25px h-25px">6</a></li>
                        <li class="page-item next"><a href="#"  class="page-link min-w-25px h-25px"><i class="next"></i></a></li>
                    </ul>
                </div>


            </div>
        </div>
        <!--begin::Body-->
    </div>
@stop
@push('admin-css')
@endpush
@push('admin-js')
    @include('admin.theme.errors')
@endpush

