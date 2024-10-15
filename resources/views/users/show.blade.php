<x-app-layout>
    <div>
        {{--Breadcrumb start--}}
        <div class="mb-6">
            <x-breadcrumb :breadcrumb-items="$breadcrumbItems" :page-title="$pageTitle" />
        </div>
        {{--Breadcrumb end--}}

        <div class="max-w-4xl p-5 pb-6 m-auto bg-white rounded-md dark:bg-slate-800">

            <div class="grid sm:grid-cols-2 gap-x-8 gap-y-4">
                <div class="input-group">
                    {{--Name input start--}}
                    <label for="name" class="inline-block pb-2 text-sm font-medium font-Inter text-textColor dark:text-white">
                        {{ __('Name') }}
                    </label>
                    <input name="name" type="text" id="name" class="w-full p-3 py-2 rounded cursor-not-allowed bg-slate-200 dark:bg-slate-900 dark:text-slate-300"
                           placeholder="{{ __('Enter your name') }}" value="{{ $user->name }}" disabled>
                </div>
                {{--Name input end--}}
                {{--Email input start--}}
                <div class="input-group">
                    <label for="email" class="inline-block pb-2 text-sm font-medium font-Inter text-textColor dark:text-white">
                        {{ __('Email') }}
                    </label>
                    <input name="email" type="email" id="email" class="w-full p-3 py-2 rounded cursor-not-allowed bg-slate-200 dark:bg-slate-900 dark:text-slate-300"
                           placeholder="{{ __('Enter your email') }}" value="{{ $user->email }}" required disabled>
                </div>
                {{--Email input end--}}
            </div>
        </div>

    </div>
</x-app-layout>
