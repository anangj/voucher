<x-app-layout>
    <div>
        <div class="mb-6 ">
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />
        </div>

        {{-- Alert Messages --}}
        @if (session('message'))
            <x-alert :message="session('message')" :type="'success'" />
        @endif

        <div class="card p-6">
            <h2 class="text-lg font-bold mb-4">{{ __('Edit Voucher') }}</h2>

            <form action="{{ route('vouchers.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="patient_name" class="font-bold">{{ __('Paket Name:') }}</label>
                        <input type="text" id="paket_name" name="paket_name" value="{{ $data->paketVoucher->name }}" class="form-control w-full" readonly>
                    </div>
                    <!-- Patient Info -->
                    <div>
                        <label for="patient_name" class="font-bold">{{ __('Patient Name:') }}</label>
                        <input type="text" id="patient_name" name="patient_name" value="{{ $data->patient->name }}" class="form-control w-full" readonly>
                    </div>

                    <!-- Voucher Info -->
                    {{-- <div>
                        <label for="voucher_no" class="font-bold">{{ __('Voucher Number:') }}</label>
                        <input type="text" id="voucher_no" name="voucher_no" value="{{ $data->voucher_no }}" class="form-control w-full" readonly>
                    </div> --}}

                    <!-- Purchase Date -->
                    <div>
                        <label for="purchase_date" class="font-bold">{{ __('Purchase Date:') }}</label>
                        <input type="date" id="purchase_date" name="purchase_date" value="{{ $data->purchase_date }}" class="form-control w-full" readonly>
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label for="expiry_date" class="font-bold">{{ __('Expiry Date:') }}</label>
                        <input type="date" id="expiry_date" name="expiry_date" value="{{ $data->expiry_date }}" class="form-control w-full" required>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="font-bold">{{ __('Status:') }}</label>
                        <select name="status" id="status" class="form-control w-full">
                            <option value="active" {{ $data->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ $data->status == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                        {{ __('Update Voucher') }}
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
