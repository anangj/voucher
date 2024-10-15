<x-guest-layout>
    <div class="flex flex-col justify-center h-full auth-box">
        <div class="flex justify-center mb-6 text-center mobile-logo lg:hidden">
            <div class="inline-flex items-center justify-center mb-10">
                <x-application-logo />
                <span class="text-xl font-bold ltr:ml-3 rtl:mr-3 font-Inter text-slate-900 dark:text-white">DashCode</span>
            </div>
        </div>
        <div class="w-full px-4 sms:px-0 sm:w-[480px]">
            <div class="text-center">
                <h4 class="font-medium">
                    {{ __('Forgot Your Password?') }}
                </h4>
                <p class="px-4 py-3 mt-8 text-xs font-semibold bg-slate-100 sm:text-base text-textColor ">
                    {{ __('Enter your Email and instructions will be sent to you!') }}
                </p>
            </div>
            {{-- Session Status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                {{-- Email --}}
                <div class="mt-3">
                    <label for="email" class="block capitalize form-label">
                        {{ __('Email') }}
                    </label>
                    <input type="email" name="email" id="email" class="py-2 form-control " placeholder="{{ __('Type your email') }}" required value="{{ old('email') }}">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />

                <button type="submit" class="block w-full mt-3 text-center btn btn-dark">
                    {{ __('Send recovery email') }}
                </button>
            </form>
            <p class="md:max-w-[345px] mx-auto font-normal text-slate-500 dark:text-slate-400 mt-12 uppercase text-sm">
                {{ __('Remember your password?') }}
                <a href="{{ route('login') }}" class="font-medium text-slate-900 dark:text-white hover:underline">
                    {{ __('Sign In') }}
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
