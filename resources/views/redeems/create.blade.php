<x-app-layout>
    <div class="max-w-xl m-auto">

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="max-w-xl mx-auto p-6 bg-white shadow-md rounded-md">
            <h2 class="text-lg font-bold mb-4">{{ __('Redeem Voucher') }}</h2>

            <form action="{{ route('vouchers.redeem') }}" method="POST">
                @csrf

                <!-- Voucher Details Section -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label class="block text-gray-700">{{ __('Nama Paket') }}</label>
                        <input type="text" value="{{ $packageName }}" class="form-control w-full p-2 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700">{{ __('Tanggal Pembelian') }}</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($purchaseDate)->format('d M Y') }}" class="form-control w-full p-2 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700">{{ __('No. Voucher') }}</label>
                        <input type="text" id="voucher_no" name="voucher_no" value="{{ $voucherNo }}" class="form-control w-full p-2 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700">{{ __('Masa Berlaku') }}</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($expiryDate)->format('d M Y') }}" class="form-control w-full p-2 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700">{{ __('Dapat digunakan oleh:') }}</label>
                        <select class="form-control w-full p-2 border border-gray-300 rounded-md" name="patient_id" required>
                            <option value="">{{ __('Pilih pasien') }}</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->patient_name }} - {{ $patient->patient_phone }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="block text-gray-700">{{ __('Sisa Voucher') }}</label>
                        <input type="text" value="{{ $remainingUses }}" class="form-control w-full p-2 border border-gray-300 rounded-md" readonly>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                        {{ __('Gunakan Voucher') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
