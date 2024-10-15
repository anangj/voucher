<!-- BEGIN: Sidebar -->
<div class="sidebar-wrapper group w-0 hidden xl:w-[248px] xl:block">
    <div id="bodyOverlay" class="fixed top-0 z-10 hidden w-screen h-screen bg-opacity-50 bg-slate-900 backdrop-blur-sm">
    </div>
    <div class="logo-segment">

        <!-- Application Logo -->
        <x-application-logo />

        <!-- Sidebar Type Button -->
        <div id="sidebar_type" class="text-lg cursor-pointer text-slate-900 dark:text-white">
            <iconify-icon class="sidebarDotIcon extend-icon text-slate-900 dark:text-slate-200" icon="fa-regular:dot-circle"></iconify-icon>
            <iconify-icon class="sidebarDotIcon collapsed-icon text-slate-900 dark:text-slate-200" icon="material-symbols:circle-outline"></iconify-icon>
        </div>
        <button class="inline-block text-2xl sidebarCloseIcon md:hidden">
            <iconify-icon class="text-slate-900 dark:text-slate-200" icon="clarity:window-close-line"></iconify-icon>
        </button>
    </div>
    <div id="nav_shadow" class="nav_shadow h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-none
      opacity-0"></div>
    <div class="sidebar-menus bg-white dark:bg-slate-800 py-2 px-4 h-[calc(100%-80px)] z-50" id="sidebar_menus">
        <ul class="sidebar-menu">
            <li class="sidebar-menu-title">{{ __('MENU') }}</li>
            <li>
                <a href="{{ route('dashboard.index') }}" class="navItem {{ (request()->is('dashboard*')) ? 'active' : '' }}">
                    <span class="flex items-center">
                        <iconify-icon class=" nav-icon" icon="heroicons-outline:home"></iconify-icon>
                        <span>{{ __('Home') }}</span>
                    </span>
                </a>
            </li>

            @can('menu package_voucher')
                <li>
                    <a href="{{ route('packages.index') }}" class="navItem {{ (request()->is('packages*')) ? 'active' : '' }}">
                        <span class="flex items-center">
                            <iconify-icon class=" nav-icon" icon="mdi:package"></iconify-icon>
                            <span>{{ __('Package Voucher') }}</span>
                        </span>
                    </a>
                </li>
            @endcan

            @can('menu patient')
                <li>
                    <a href="{{ route('patient.index') }}" class="navItem {{ (request()->is('patient*')) ? 'active' : '' }}">
                        <span class="flex items-center">
                            <iconify-icon class=" nav-icon" icon="mdi:patient"></iconify-icon>
                            <span>{{ __('Patient') }}</span>
                        </span>
                    </a>
                </li>
            @endcan


            @can('menu voucher')
                <li>
                    <a href="{{ route('vouchers.index') }}" class="navItem {{ (request()->is('vouchers*')) ? 'active' : '' }}">
                        <span class="flex items-center">
                            <iconify-icon class=" nav-icon" icon="mdi:voucher"></iconify-icon>
                            <span>{{ __('Voucher') }}</span>
                        </span>
                    </a>
                </li>
            @endcan

            {{-- @can('menu redeem')
                <li>
                    <a href="{{ route('redeem.index') }}" class="navItem {{ (request()->is('redeem*')) ? 'active' : '' }}">
                        <span class="flex items-center">
                            <iconify-icon class=" nav-icon" icon="material-symbols:redeem"></iconify-icon>
                            <span>{{ __('Redeem') }}</span>
                        </span>
                    </a>
                </li>
            @endcan --}}

            <!-- Database -->
            @can('menu database_backup')
                <li>
                    <a href="{{ route('database-backups.index') }}" class="navItem {{ (request()->is('database-backups*')) ? 'active' : '' }}">
                        <span class="flex items-center">
                            <iconify-icon class=" nav-icon" icon="iconoir:database-backup"></iconify-icon>
                            <span>{{ __('Database Backup') }}</span>
                        </span>
                    </a>
                </li>
            @endcan

            @can('menu setting')
            <!-- Settings -->
            <li>
                <a href="{{ route('general-settings.show') }}" class="navItem {{ (request()->is('general-settings*')) || (request()->is('users*')) || (request()->is('roles*')) || (request()->is('profiles*')) || (request()->is('permissions*')) ? 'active' : '' }}">
                    <span class="flex items-center">
                        <iconify-icon class=" nav-icon" icon="material-symbols:settings-outline"></iconify-icon>
                        <span>{{ __('Settings') }}</span>
                    </span>
                </a>
            </li>

            @endcan

        </ul>
    </div>
</div>
<!-- End: Sidebar -->
