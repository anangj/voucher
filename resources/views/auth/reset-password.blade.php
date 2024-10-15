<x-guest-layout>
    <div class="flex flex-col justify-center h-full auth-box">
        <div class="w-full px-4 sms:px-0 sm:w-[450px]">
            <div class="flex justify-center mb-6 text-center mobile-logo lg:hidden">
                <div class="inline-flex items-center justify-center mb-10">
                    <x-application-logo />
                </div>
            </div>
            <div class="text-center">
                <h4 class="font-medium">{{ __('Reset password') }}</h4>
            </div>
            <form method="POST" action="{{ route('password.update') }}" class="mt-8 md:mt-12">
                @csrf
                {{-- Password Reset Token --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                {{-- Email --}}
                <div class="mt-3">
                    <label for="email" class="inline-block mb-2 text-sm font-medium text-textColor">
                        {{ __('Email') }}
                    </label>
                    <input type="email" name="email" id="email" class="w-full border border-slate-300 bg-[#FBFBFB] py-[10px] px-4 outline-none focus:outline-none focus:ring-0 focus:border-[#000000] shadow-none rounded-md text-base text-black read-only:cursor-not-allowed read-only:bg-slate-200" placeholder="{{ __('Type your email') }}" required readonly value="{{ $request->email }}">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Password --}}
                <div class="relative mt-4">
                    <label for="password" class="inline-block mb-2 text-sm font-medium text-textColor">
                        {{ __('Password') }}
                    </label>
                    <input type="password" name="password" id="password" class="w-full border border-slate-300 bg-[#FBFBFB] py-[10px] px-4 outline-none focus:outline-none focus:ring-0 focus:border-[#000000] shadow-none rounded-md text-base text-black pr-11" placeholder="{{ __('New Password') }}" required autocomplete="new-password">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                {{-- Confirm Password --}}
                <div class="relative mt-4">
                    <label for="password_confirmation" class="inline-block mb-2 text-sm font-medium text-textColor">
                        {{ __(' Confirm Password') }}
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border border-slate-300 bg-[#FBFBFB] py-[10px] px-4 outline-none focus:outline-none focus:ring-0 focus:border-[#000000] shadow-none rounded-md text-base text-black pr-11" placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                <button type="submit" class="w-full px-3 py-3 mt-8 text-base font-medium text-white transition-all duration-500 rounded-md bg-slate-900 hover:bg-slate-800">
                    {{ __('Reset password') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
