<x-app-layout>
    <div class="space-y-8">
        <div class="items-center justify-between block mb-6 sm:flex">
            {{-- Breadcrumb --}}
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />

            <div class="text-end">
                <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2 !px-3" href="{{ route('packages.index') }}">
                    <iconify-icon class="mr-1 text-lg" icon="ic:outline-arrow-back"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="overflow-hidden rounded-md">
            <div class="px-5 bg-white dark:bg-slate-800 py-7">
                <form method="POST" action="{{ route('packages.update', $data)}}">
                    @csrf
                    @method('PUT')

                    {{-- Package Voucher Name --}}
                    <div class="form-group">
                        <label for="name">{{__('Package Voucher Name')}}</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $data->name) }}" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>


                    {{-- Voucher Type --}}
                    <div class="form-group">
                        <label for="voucher_type">{{__('Voucher Type')}}</label>
                        <select class="form-control" id="voucher_type" name="voucher_type">
                            <option value="discount" {{ old('voucher_type', $data->voucher_type) == 'discount' ? 'selected' : '' }}>Discount</option>
                            <option value="service" {{ old('voucher_type', $data->voucher_type) == 'service' ? 'selected' : '' }}>Service</option>
                        </select>
                        <x-input-error :messages="$errors->get('voucher_type')" class="mt-2"/>
                    </div>

                    {{-- Description --}}
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea id="description" name="description" class="form-control">{{ old('description', $data->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                    </div>

                    {{-- Max Uses --}}
                    <div class="form-group">
                        <label for="max_uses">{{__('Max Uses per Voucher')}}</label>
                        <input type="number" id="max_uses" name="max_uses" class="form-control" value="{{ old('max_uses', $data->max_uses) }}" required min="1">
                        <x-input-error :messages="$errors->get('max_uses')" class="mt-2"/>
                    </div>

                    {{-- Max Sharing --}}
                    <div class="form-group">
                        <label for="max_sharing">{{__('Max Sharing')}}</label>
                        <input type="number" id="max_sharing" name="max_sharing" class="form-control" value="{{ old('max_sharing', $data->max_sharing) }}" required min="0">
                        <x-input-error :messages="$errors->get('max_sharing')" class="mt-2"/>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex items-center justify-end mt-4">
                        <button class="btn inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1 mt-4 !px-3 !py-2">
                            <span class="flex items-center">
                                <span> @lang('Save Changes')</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
