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



                    {{-- Max Sharing --}}
                    <div class="form-group">
                        <label for="max_sharing">{{__('Max Sharing')}}</label>
                        <input type="number" id="max_sharing" name="max_sharing" class="form-control" value="{{ old('max_sharing', $data->max_sharing) }}" required min="0">
                        <x-input-error :messages="$errors->get('max_sharing')" class="mt-2"/>
                    </div>

                    <div class="form-group">
                        <label for="amount">{{ __('Amount') }}</label>
                        <input type="text" id="amount" name="amount" class="form-control" value="{{ $data->formatted_amount }}" >
                    </div>
                    

                    {{-- Amount --}}
                    {{-- <div class="form-group">
                        <label for="amount">{{ __('Amount') }}</label>
                        <input 
                            type="text" 
                            id="amount" 
                            class="form-control" 
                            value="{{ old('amount', number_format($data->amount, 0, ',', '.')) }}" 
                            oninput="formatRupiah(this)" 
                            placeholder="Rp 0"
                        />
                        <input 
                            type="hidden" 
                            id="amount" 
                            name="amount" 
                            value="{{ old('amount', number_format($data->amount, 0, ',', '.')) }}" 
                        />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2"/>
                    </div> --}}
                    

                    {{-- Quill Editor for TnC --}}
                    <div class="form-group mt-4">
                        <label for="tnc_editor">{{__('Terms and Conditions')}}</label>
                        <div id="tnc_editor" class="bg-gray-100 p-2 rounded"></div>
                        {{-- Hidden Input to Save Quill Data --}}
                        <textarea name="tnc" id="tnc" style="display:none;">{{ old('tnc', $data->tnc) }}</textarea>
                        <x-input-error :messages="$errors->get('tnc')" class="mt-2"/>
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
    @push('scripts')
        <script type="module">
            // Initialize the Quill editor with preloaded data
            const quill = new Quill('#tnc_editor', {
                placeholder: 'Edit Terms and Conditions...',
                theme: 'snow',
            });

            // Load initial content into Quill
            quill.root.innerHTML = {!! json_encode(old('tnc', $data->tnc)) !!};

            // On form submission, copy Quill content to the hidden input
            const form = document.getElementById('editPackageForm');
            form.addEventListener('submit', function() {
                const tncContent = quill.root.innerHTML;
                document.getElementById('tnc').value = tncContent;
            });

            function formatRupiah(input) {
                let value = input.value.replace(/[^,\d]/g, ''); // Remove non-numeric characters
                let formatted = '';
                let split = value.split(',');
                let remainder = split[0].length % 3;
                let rupiah = split[0].substr(0, remainder);
                let thousands = split[0].substr(remainder).match(/\d{3}/g);

                // Add thousands separator
                if (thousands) {
                    let separator = remainder ? '.' : '';
                    rupiah += separator + thousands.join('.');
                }

                formatted = rupiah + (split[1] !== undefined ? ',' + split[1] : '');
                input.value = `Rp ${formatted}`;

                // Set the hidden input to store the numeric value
                document.getElementById('amount').value = value;
            }
        </script>
    @endpush
</x-app-layout>
