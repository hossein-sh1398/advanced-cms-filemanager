@foreach($adminMenus as $adminMenu)
    @if(isset($adminMenu['sub']) and count($adminMenu['sub']) > 0)
        @canany($adminMenu['permissions'])
            <div data-kt-menu-trigger="click"
                class="menu-item menu-accordion {{ activeAdminSubMenu($adminMenu) ? 'here show' : '' }}">
                <span class="menu-link">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">{{ $adminMenu['title'] }}</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion menu-active-bg">
                    @include('admin.theme.elements.menu', ['adminMenus' => $adminMenu['sub'] ?? null])
                </div>
            </div>
        @endcan
    @else
        @canany($adminMenu['permissions'])
            <div class="menu-item">
                <a class="menu-link {{ activeAdminMenu($adminMenu) ? 'active' : '' }}"
                href="{{ $adminMenu['route'] }}">
                    <span class="menu-bullet">
                        <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">{{ $adminMenu['title'] }}</span>
                </a>

            </div>
        @endcanany
    @endif
@endforeach
