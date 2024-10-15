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
                <!-- Individual Form Fields for QR Code Data -->
                <div class="form-group mb-4">
                    <label for="voucher_no" class="block text-gray-700">{{ __('Voucher No') }}</label>
                    <input type="text" id="voucher_no" name="voucher_no" class="form-control w-full p-2 border border-gray-300 rounded-md" required readonly>
                </div>

                <!-- Hidden QR Code scanner (until button click) -->
                <div id="qr-reader" class="w-full h-74 bg-gray-100 mb-4 hidden"></div>

                <!-- Button to start QR scanner -->
                <button type="button" id="startScanner" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                    {{ __('Start Redeem') }}
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
            document.addEventListener('DOMContentLoaded', function () {
                let html5QrCode;

                // Event listener for starting the scanner on button click
                document.getElementById('startScanner').addEventListener('click', function () {
                    // Initialize the scanner
                    if (!html5QrCode) {
                        html5QrCode = new Html5Qrcode("qr-reader");
                    }

                    // Display the QR reader div and the stop button, hide the start button
                    document.getElementById('qr-reader').classList.remove('hidden');
                    document.getElementById('startScanner').classList.add('hidden');
                    document.getElementById('stopScanner').classList.remove('hidden');

                    // Start the QR scanner
                    html5QrCode.start(
                        { facingMode: "environment" }, // Rear camera
                        {
                            fps: 10,    // Frames per second
                            qrbox: 500  // Box size for QR scanning
                        },
                        (decodedText, decodedResult) => {
                            // Assuming decodedText contains JSON, we parse the JSON
                            try {
                                const data = JSON.parse(decodedText);

                                // Place parsed data into individual form fields
                                document.getElementById('voucher_no').value = data.voucher_no || '';
                                // document.getElementById('patient_name').value = data.patient_name || '';
                                // document.getElementById('expiry_date').value = data.expiry_date || '';
                                // document.getElementById('purchase_date').value = data.purchase_date || '';

                                console.log(`Decoded data:`, data);
                                // Auto-stop the camera after successful scan
                                html5QrCode.stop().then(() => {
                                    console.log("QR Scanner stopped automatically after scan.");
                                    document.getElementById('qr-reader').classList.add('hidden');
                                    document.getElementById('startScanner').classList.remove('hidden');
                                    document.getElementById('stopScanner').classList.add('hidden');
                                }).catch((err) => {
                                    console.error("Failed to stop QR scanner after scan:", err);
                                });
                            } catch (error) {
                                console.error('Failed to parse QR code data:', error);
                                alert('Invalid QR code format.');
                            }
                        },
                        (errorMessage) => {
                            // Handle errors in scanning (ignored for now)
                            console.warn(`QR scan failed: ${errorMessage}`);
                        }
                    ).catch((err) => {
                        console.error(`Unable to start the QR scanner: ${err}`);
                        alert("Error starting camera: " + err);
                    });
                });

                // Event listener for stopping the scanner on button click
                document.getElementById('stopScanner').addEventListener('click', function () {
                    if (html5QrCode) {
                        html5QrCode.stop().then(() => {
                            // QR Code scanning is stopped.
                            document.getElementById('qr-reader').classList.add('hidden');
                            document.getElementById('startScanner').classList.remove('hidden');
                            document.getElementById('stopScanner').classList.add('hidden');
                            console.log("QR Scanner stopped.");
                        }).catch((err) => {
                            // Handle the error (if any)
                            console.error("Failed to stop QR scanner: ", err);
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
