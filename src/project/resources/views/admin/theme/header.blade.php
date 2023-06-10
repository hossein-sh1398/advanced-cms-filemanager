<div class="app-container container-fluid d-flex align-items-stretch justify-content-between">
    <!--begin::sidebar mobile toggle-->
    <div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
        <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
            <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
            <span class="svg-icon svg-icon-1">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                        fill="currentColor" />
                    <path opacity="0.3"
                        d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                        fill="currentColor" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
    </div>
    <!--end::sidebar mobile toggle-->
    <!--begin::Mobile logo-->
    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
        <a href="#" class="d-lg-none">
            <img alt="Logo" src="{{ url($logo) }}" class="h-30px" />
        </a>
    </div>
    <!--end::Mobile logo-->
    <!--begin::Header wrapper-->
    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
        <!--begin::Menu wrapper-->
        <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
            data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
            data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="end"
            data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
            data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
            data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
            <!--begin::Menu-->
            <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
                id="kt_app_header_menu" data-kt-menu="true">
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                    class="menu-item here show menu-lg-down-accordion menu-here-bg me-0 me-lg-2">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-title">امروز : {{ getToday('Y/m/d ساعت H:i ') }}</span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
        <!--begin::Navbar-->
        <div class="app-navbar flex-shrink-0">
            <!--begin::Notifications-->
            <x-header-notification-component></x-header-notification-component>
            <!--end::Notifications-->
            <!--begin::User menu-->
            <div class="app-navbar-item ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                <x-profile-component></x-profile-component>
                <!--end::User account menu-->
                <!--end::Menu wrapper-->
            </div>
            <!--end::User menu-->
        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header wrapper-->
</div>