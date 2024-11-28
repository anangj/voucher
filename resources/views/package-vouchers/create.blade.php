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

        <form id='paket' action="{{route('packages.store')}}" method="POST" class="max-w-4xl m-auto" enctype="multipart/form-data">
            @csrf
            <div class="p-5 pb-6 bg-white rounded-md dark:bg-slate-800">
                <div class="grid sm:grid-cols-1 gap-x-8 gap-y-4">

                    <div class="form-group">
                        <label for="name">Package Voucher Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="total_distribute">{{ __('Total Vouchers to Distribute') }}</label>
                        <input type="number" id="total_distribute" name="total_distribute" class="form-control" value="{{ old('total_distribute') }}" required min="1">
                        <small class="form-text text-muted">This defines how many vouchers will be given to each patient.</small>
                    </div>

                    <div class="form-group">
                        <label for="voucher_type">Voucher Type</label>
                        <select class="form-control" id="voucher_type" name="voucher_type" required>
                            <option value="discount">Discount</option>
                            <option value="service">Service</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="max_sharing">Max Sharing include patient</label>
                        <input type="number" class="form-control" id="max_sharing" name="max_sharing" required min="0">
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required min="0">
                    </div>

                    <div class="form-group">
                        <label for="logo_unit">{{ __('Upload Logo') }}</label>
                        <input type="file" class="form-control" id="logo_unit" name="logo_unit" accept="logo_unit/*">
                    </div>

                    <div class="form-group">
                        <label for="image">{{ __('Upload Cover') }}</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Upload an image representing the package voucher.</small>
                    </div>

                    <!-- Quill Editor for TnC -->
                    <div id="editor"></div>

                    <!-- Hidden input to store Quill content -->
                    <textarea name="tnc" style="display:none" id="hiddenArea"></textarea>

                </div>
                <button type="submit" class="inline-flex justify-center w-full mt-4 btn btn-dark">
                    {{ __('Create Package Voucher') }}
                </button>
            </div>
        </form>

    </div>
    @push('scripts')
        <script type="module">
            // Initialize the Quill editor
            const quill = new Quill('#editor', {
                placeholder: 'Terms and Conditions',
                theme: 'snow',
            });

            $("#paket").on("submit",function() {
                $("#hiddenArea").val(quill.root.innerHTML)
            });
        </script>
    @endpush
</x-app-layout>
