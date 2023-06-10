<!--begin::Menu wrapper-->
<div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="click"
     data-kt-menu-attach="parent" data-kt-menu-placement="bottom-start">
    <img src="{{ url($user->avatar()) }}" alt="user" style="object-fit: cover" />
</div>
<!--begin::User account menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
     data-kt-menu="true">
    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <div class="menu-content d-flex align-items-center px-3">
            <!--begin::Username-->
            <div class="d-flex flex-column">
                <div class="fw-bold d-flex align-items-center fs-5">
                    {{ $user->name ?? '' }}
                    {{-- <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span> --}}
                </div>
                <a href="#"
                   class="fw-semibold text-muted text-hover-primary fs-7">{{ $user->email ?? '' }}</a>
            </div>
            <!--end::Username-->
        </div>
    </div>
    <!--end::Menu item-->
    <!--begin::Menu separator-->
    <div class="separator my-2"></div>
    <!--end::Menu separator-->
    <!--begin::Menu item-->
    <div class="menu-item px-5">
        <a href="{{route('admin.profile.index')}}" class="menu-link px-5">پروفایل</a>
    </div>
    <!--end::Menu item-->
    <!--begin::Menu item-->
    <div class="menu-item px-5">
        <a href="{{ route('auth.logout') }}" class="menu-link px-5">خروج</a>
    </div>
    <!--end::Menu item-->
</div>
