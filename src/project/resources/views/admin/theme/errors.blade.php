@if($errors->any())
    <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
        <!--begin::Svg Icon | path: icons/duotune/general/gen048.svg-->
        <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.3"
                      d="M12 22C13.6569 22 15 20.6569 15 19C15 17.3431 13.6569 16 12 16C10.3431 16 9 17.3431 9 19C9 20.6569 10.3431 22 12 22Z"
                      fill="currentColor"/>
                <path d="M19 15V18C19 18.6 18.6 19 18 19H6C5.4 19 5 18.6 5 18V15C6.1 15 7 14.1 7 13V10C7 7.6 8.7 5.6 11 5.1V3C11 2.4 11.4 2 12 2C12.6 2 13 2.4 13 3V5.1C15.3 5.6 17 7.6 17 10V13C17 14.1 17.9 15 19 15ZM11 10C11 9.4 11.4 9 12 9C12.6 9 13 8.6 13 8C13 7.4 12.6 7 12 7C10.3 7 9 8.3 9 10C9 10.6 9.4 11 10 11C10.6 11 11 10.6 11 10Z"
                      fill="currentColor"/>
            </svg>
        </span>

        <!--end::Svg Icon-->
        <div class="d-flex flex-column">
            <h4 class="mb-1 text-danger">خطای سیستمی</h4>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <!--begin::Close-->
        <button type="button"
                class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                data-bs-dismiss="alert">
            <span class="svg-icon svg-icon-1 svg-icon-primary">
                <!--begin::Svg Icon | path: C:/wamp64/www/keenthemes/core/html/src/media/icons/duotune/arrows/arr061.svg-->
                <span class="svg-icon svg-icon-muted svg-icon-2hx">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                              transform="rotate(-45 6 17.3137)"
                              fill="currentColor"/>
                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                              fill="currentColor"/>
                    </svg>
                </span>
                <!--end::Svg Icon-->
            </span>
        </button>
        <!--end::Close-->
    </div>
@endif
<?php
session()->forget('errors');