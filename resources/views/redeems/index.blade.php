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

            <form action="{{ route('redeem.create') }}" method="POST">
                @csrf
                <!-- Individual Form Fields for QR Code Data -->
                <div class="form-group mb-4">
                    <label for="voucher_no" class="block text-gray-700">{{ __('Voucher No') }}</label>
                    <input type="text" id="voucher_no" name="voucher_no" class="form-control w-full p-2 border border-gray-300 rounded-md" required >
                </div>

                <input type="hidden" id="patient_id" name="patient_id">
                <input type="hidden" id="paket_voucher_name" name="paket_voucher_name">
                <input type="hidden" id="purchase_date" name="purchase_date">
                <input type="hidden" id="expiry_date" name="expiry_date">

                <!-- Hidden QR Code scanner (until button click) -->
                <div id="qr-reader" class="w-full h-74 bg-gray-100 mb-4 hidden"></div>

                <!-- Button to start QR scanner -->
                <button type="button" id="startScanner" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                    {{ __('Start Camera') }}
                </button>

                <!-- Button to stop QR scanner (initially hidden) -->
                <button type="button" id="stopScanner" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 mt-4 hidden">
                    {{ __('Stop Camera') }}
                </button>

                <!-- Submit button -->
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 mt-4">
                    {{ __('Validate Voucher') }}
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            
        </script>
    @endpush
</x-app-layout>
