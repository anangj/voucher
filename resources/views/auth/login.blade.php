<x-guest-layout>
    <div class="flex flex-col justify-center h-full auth-box">
        <div class="flex justify-center mb-6 text-center mobile-logo lg:hidden">
            <div class="inline-flex items-center justify-center mb-10">
                <x-application-logo />
            </div>
        </div>
        <div class="text-center ">
            <h4 class="font-medium"> {{ __('Sign in') }}</h4>
        </div>

        <!-- START::LOGIN FORM -->
        <x-login-form></x-login-form>
        <!-- END::LOGIN FORM -->
        </div>
    </div>
</x-guest-layout>
