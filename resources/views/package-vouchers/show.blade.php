<x-app-layout>
    <div>
        <div class="items-center justify-between block mb-6 sm:flex">
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />
            <div class="text-end">
                <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2 !px-3" href="{{ route('packages.index') }}">
                    <iconify-icon class="mr-1 text-lg" icon="ic:outline-arrow-back"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>

        <div class="max-w-4xl p-5 pb-6 m-auto bg-white rounded-md dark:bg-slate-800">
            <div class="grid sm:grid-cols-2 gap-x-8 gap-y-4">
                <div class="input-group">
                    <label for="name" class="inline-block pb-2 text-sm font-medium font-Inter text-textColor dark:text-white">
                        {{ __('Package Voucher Name') }}
                    </label>
                    <input name="name" type="text" id="name" class="w-full p-3 py-2 rounded cursor-not-allowed bg-slate-200 dark:bg-slate-900 dark:text-slate-300"
                            value="{{$data->name}}" disabled>
                </div>

                <div class="input-group">
                    <label for="description" class="inline-block pb-2 text-sm font-medium font-Inter text-textColor dark:text-white">
                        {{ __('Description') }}
                    </label>
                    <input name="description" type="text" id="description" class="w-full p-3 py-2 rounded cursor-not-allowed bg-slate-200 dark:bg-slate-900 dark:text-slate-300"
                            value="{{$data->description}}" disabled>
                </div>

            

                <div class="input-group">
                    <label for="voucher_type" class="inline-block pb-2 text-sm font-medium font-Inter text-textColor dark:text-white">
                        {{ __('Voucher Type') }}
                    </label>
                    <input name="voucher_type" type="text" id="voucher_type" class="w-full p-3 py-2 rounded cursor-not-allowed bg-slate-200 dark:bg-slate-900 dark:text-slate-300"
                            value="{{$data->voucher_type}}" disabled>
                </div>


                <div class="input-group">
                    <label for="max_sharing" class="inline-block pb-2 text-sm font-medium font-Inter text-textColor dark:text-white">
                        {{ __('Maximal Sharing') }}
                    </label>
                    <input name="max_sharing" type="text" id="max_sharing" class="w-full p-3 py-2 rounded cursor-not-allowed bg-slate-200 dark:bg-slate-900 dark:text-slate-300"
                            value="{{$data->max_sharing}}" disabled>
                </div>

                <div class="input-group">
                    <label for="amount" class="inline-block pb-2 text-sm font-medium font-Inter text-textColor dark:text-white">
                        {{ __('Amount') }}
                    </label>
                    <input name="amount" type="text" id="amount" 
                           class="w-full p-3 py-2 rounded cursor-not-allowed bg-slate-200 dark:bg-slate-900 dark:text-slate-300"
                           value="Rp {{ number_format($data->amount, 0, ',', '.') }}" disabled>
                </div>
                

                <div class="input-group">
                    <label for="total_distribute" class="inline-block pb-2 text-sm font-medium font-Inter text-textColor dark:text-white">
                        {{ __('Total Distribute') }}
                    </label>
                    <input name="total_distribute" type="text" id="total_distribute" class="w-full p-3 py-2 rounded cursor-not-allowed bg-slate-200 dark:bg-slate-900 dark:text-slate-300"
                            value="{{$data->total_distribute}}" disabled>
                </div>

            </div>
            <div class="mt-6">
                <label for="tnc" class="inline-block pb-2 text-sm font-medium font-Inter text-textColor dark:text-white">
                    {{ __('Terms and Conditions') }}
                </label>
                <div id="quillContent" class="p-4 bg-slate-200 rounded dark:bg-slate-900 dark:text-slate-300">
                    {!! $data->tnc !!}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
