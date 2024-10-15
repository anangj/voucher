<div class="z-[9] sticky top-0" id="app_header">
    <div class="app-header z-[999] bg-white dark:bg-slate-800 shadow-sm dark:shadow-slate-700">
        <div class="flex items-center justify-between h-full">
            <div class="flex items-center space-x-4 md:space-x-4 rtl:space-x-reverse vertical-box">
                <div class="inline-block xl:hidden">
                    <x-application-logo class="mobile-logo" />
                </div>
                <button class="hidden smallDeviceMenuController open-sdiebar-controller xl:hidden md:inline-block">
                    <iconify-icon class="leading-none bg-transparent relative text-xl top-[2px] text-slate-900 dark:text-white" icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
                <button class="sidebarOpenButton text-xl text-slate-900 dark:text-white !ml-0">
                    <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
                </button>
                <x-header-search />
            </div>
            <!-- end vertcial -->

            <div class="items-center space-x-4 rtl:space-x-reverse horizental-box">
                <x-application-logo />
                <button class="hidden smallDeviceMenuController open-sdiebar-controller xl:hidden md:inline-block">
                    <iconify-icon
                        class="leading-none bg-transparent relative text-xl top-[2px] text-slate-900 dark:text-white"
                        icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
                <x-header-search />
            </div>
            <!-- end horizontal -->

            <!-- start horizontal nav -->
            <x-topbar-menu />
            <!-- end horizontal nav -->

            <div class="flex items-center space-x-3 nav-tools lg:space-x-5 rtl:space-x-reverse leading-0">
                {{-- <x-nav-lang-dropdown /> --}}
                <x-dark-light />
                <x-gray-scale />
                {{-- <x-nav-message-dropdown /> --}}
                {{-- <x-nav-notification-dropdown /> --}}
                <x-nav-user-dropdown />
                <button class="block smallDeviceMenuController md:hidden leading-0">
                    <iconify-icon class="text-2xl cursor-pointer text-slate-900 dark:text-white" icon="heroicons-outline:menu-alt-3"></iconify-icon>
                </button>
                <!-- end mobile menu -->
            </div>
            <!-- end nav tools -->
        </div>
    </div>
</div>

<!-- BEGIN: Search Modal -->
<div class="fixed inset-0 top-0 left-0 hidden w-full h-full overflow-x-hidden overflow-y-auto outline-none modal fade bg-slate-900/40 backdrop-filter backdrop-blur-sm backdrop-brightness-10" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="relative w-auto pointer-events-none modal-dialog top-1/4">
        <div class="relative flex flex-col w-full text-current bg-white border-none rounded-md shadow-lg outline-none pointer-events-auto modal-content dark:bg-slate-900 bg-clip-padding">
            <form>
                <div class="relative">
                    <button class="absolute left-0 flex items-center justify-center h-full text-xl -translate-y-1/2 top-1/2 w-9 dark:text-slate-300">
                        <iconify-icon icon="heroicons-solid:search"></iconify-icon>
                    </button>
                    <input type="text" class="form-control !py-[14px] !pl-10" placeholder="Search" autofocus>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Search Modal -->
