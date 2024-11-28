<x-app-layout>
    <div>
        <div class="space-y-8">
            <div class="card p-6">
                <div class="grid xl:grid-cols-4 lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5 place-content-center">
                    <div class="flex space-x-4 h-full items-center rtl:space-x-reverse">
                        <div class="flex-none">
                            <div class="h-20 w-20 rounded-full">
                                <img src="images/all-img/main-user.png" alt="" class="w-full h-full">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-medium mb-2">
                                <span class="block font-light">Good evening,</span>
                                <span class="block">Mr. Jone Doe</span>
                            </h4>
                            <p class="text-sm dark:text-slate-300">Welcome</p>
                        </div>
                    </div>

                    <!-- Right-aligned button -->
                    <div class="flex justify-end items-center xl:col-span-4 lg:col-span-2">
                        <button id="openValidateVoucherModal" class="btn inline-flex justify-center btn-outline-dark capitalize">
                            {{ __('Validate Voucher') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- Search Patient Modal --}}
    <div id="validateModal" class="hidden fixed top-0 left-0 z-50 w-full h-full bg-gray-800 bg-opacity-50 flex justify-center items-center">
        <div class="relative bg-white p-6 rounded-lg shadow-lg w-96">
            <!-- Close Button (X) at the top-right corner -->
            <button type="button" id="closeValidateVoucherModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="text-lg font-bold mb-4">{{ __('Search Patient') }}</h3>

            <form action="{{route('vouchers.validate')}}" method="POST" class="space-y-3">
                @csrf

                <div class="form-group mb-4">
                    <label for="voucher_no" class="block text-gray-700">{{ __('Voucher No') }}</label>
                    <input type="text" id="voucher_no" name="voucher_no" class="form-control w-full p-2 border border-gray-300 rounded-md" required >
                </div>

                {{-- <input type="hidden" id="patient_id" name="patient_id">
                <input type="hidden" id="voucher_header_id" name="voucher_header_id">
                <input type="hidden" id="paket_voucher_name" name="paket_voucher_name">
                <input type="hidden" id="purchase_date" name="purchase_date">
                <input type="hidden" id="expiry_date" name="expiry_date"> --}}

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
    <script>

        // Open Modal
        document.getElementById('openValidateVoucherModal').addEventListener('click', function() {
            document.getElementById('validateModal').classList.remove('hidden');
        });

        // Close Modal
        document.getElementById('closeValidateVoucherModal').addEventListener('click', function() {
            document.getElementById('validateModal').classList.add('hidden');
        });
    </script>
    <script>
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
                            document.getElementById('voucher_no').value = data.voucher

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
