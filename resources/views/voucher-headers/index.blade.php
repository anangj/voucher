<x-app-layout>
    <div>
        <div class="mb-6">
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />
        </div>

        <!-- Filter Notification -->
        @if (request()->filled('name') || request()->filled('date') || request()->filled('status'))
            <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded-md">
                <p><strong>Notice:</strong> The results are currently filtered.</p>
                <a href="{{ route('vouchers.index') }}" class="text-blue-500 underline">Clear Filters</a>
            </div>
        @endif

        {{-- Alert start --}}
        @if (session('message'))
        <x-alert :message="session('message')" :type="'success'" />
        @endif
        {{-- Alert end --}}

        <div class="card">
            <div class="flex justify-between">
                <div class="card-header noborder">
                    <div class="flex flex-wrap items-center justify-end gap-3">
                        <div>
                            <button id="filterToggle" class="inline-flex justify-center btn btn-dark rounded-[25px] items-center !p-2 !px-3">Filter Options</button>
                        </div>

                        <div id="filterForm" class="card mb-6 p-5 hidden">
                            <form method="POST" action="{{route('vouchers-filter.index')}}">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Filter by Package Name -->
                                    <div>
                                        <label for="name">Package Name</label>
                                        <select name="name" id="name" class="form-control">
                                            <option value="">Select a Package</option>
                                            @foreach ($paket as $item)
                                                <option value="{{ $item->name }}"
                                                        {{ request('name') == $item->name ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit and Reset Buttons -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-dark">Filter</button>
                                    <a href="{{route('vouchers.index')}}" class="btn btn-light">Clear Filters</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>


            <div class="px-6 pb-6 card-body">
                <div class="-mx-6 overflow-x-auto dashcode-data-table">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="w-full divide-y table-fixed divide-slate-100 dark:divide-slate-700 data-table">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>
                                        <th scope="col" class="table-th"> {{__('Patient')}} </th>
                                        <th scope="col" class="table-th"> {{ __('Paket Voucher Name') }} </th>
                                        <th scope="col" class="table-th"> {{__('Purchase Date')}} </th>
                                        <th scope="col" class="table-th"> {{__('Expiry Date')}} </th>
                                        <th scope="col" class="table-th"> {{__('Status')}} </th>
                                        <th scope="col" class="table-th"> {{__('Actions')}} </th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-slate-100 dark:divide-slate-700 dark:bg-slate-800">
                                    @forelse ($data as $voucher)
                                    <tr>
                                        <td class="table-td">{{ $voucher->patient->name }}</td>
                                        <td class="table-td">{{ $voucher->paketVoucher->name ?? 'N/A' }}</td>
                                        <td class="table-td">{{ $voucher->purchase_date }}</td>
                                        <td class="table-td">{{ $voucher->expiry_date }}</td>
                                        <td>
                                            @if ($voucher->status == 'expired')
                                                <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-warning-500
                                                    bg-warning-500">
                                                    {{ ucfirst($voucher->status) }}
                                                </div>
                                            @elseif ($voucher->status == 'active')
                                                <div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] bg-opacity-25 text-success-500 bg-success-500">
                                                    {{ ucfirst($voucher->status) }}
                                                </div>
                                            @endif
                                        </td>

                                        <td class="table-td">
                                            <div class="flex space-x-1">
                                                @can('voucher update')
                                                <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-sm btn-warning">
                                                    <iconify-icon icon="mdi:pencil-outline" class="text-xl"></iconify-icon>
                                                </a>
                                                @endcan

                                                @can('voucher show')
                                                <a href="{{ route('vouchers.show', $voucher->id) }}" class="btn btn-sm btn-primary">
                                                    <iconify-icon icon="mdi:eye-outline" class="text-xl"></iconify-icon>
                                                </a>
                                                @endcan

                                                {{-- @if ($voucher->status === 'unassigned')
                                                <a href="{{ route('vouchers.assign', ['paketVoucherId' => $voucher->paket_voucher_id]) }}" class="btn btn-sm btn-success">
                                                    {{ __('Assign') }}
                                                </a>
                                                @endif --}}

                                                @can('voucher delete')
                                                <form id="deleteForm{{$voucher->id}}" action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST" onclick="sweetAlertDelete(event, 'deleteForm{{ $voucher->id }}')" type="submit">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <iconify-icon icon="mdi:delete-outline" class="text-xl"></iconify-icon>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="p-4 text-center">{{ __('No Vouchers Found') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Patient Modal --}}
        <div id="validateModal" class="hidden fixed top-0 left-0 z-50 w-full h-full bg-gray-800 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-lg font-bold mb-4">{{ __('Search Patient') }}</h3>

                <form action="{{route('vouchers.validate')}}" method="POST" class="space-y-3">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="voucher_no" class="block text-gray-700">{{ __('Voucher No') }}</label>
                        <input type="text" id="voucher_no" name="voucher_no" class="form-control w-full p-2 border border-gray-300 rounded-md" required readonly>
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


                    {{-- RM No --}}
                    {{-- <input type="text" id="rm_no" name="rm_no" placeholder="Enter RM No" class="form-control" autocomplete="off"> --}}

                    {{-- Name --}}
                    {{-- <input type="text" id="name" name="name" placeholder="Enter Name" class="form-control" autocomplete="off"> --}}

                    {{-- BOD (Birthdate) --}}
                    {{-- <input type="date" id="bod" name="bod" placeholder="Enter Birthdate" class="form-control"> --}}

                    {{-- Address (Alamat) --}}
                    {{-- <input type="text" id="alamat" name="alamat" placeholder="Enter Address" class="form-control" autocomplete="off"> --}}

                    {{-- <div class="flex justify-between mt-4">
                        <button type="button" id="searchPatientBtn" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                            {{ __('Validate') }}
                        </button>
                        <button type="button" id="closeValidateVoucherModal" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">
                            {{ __('Close') }}
                        </button>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="module">
            // data table validation
            $("#data-table, .data-table").DataTable({
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
                info: false,
                searching: true,
                lengthChange: true,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: `<iconify-icon icon="ic:round-keyboard-arrow-left"></iconify-icon>`,
                        next: `<iconify-icon icon="ic:round-keyboard-arrow-right"></iconify-icon>`,
                    },
                    search: "Search:",
                },
            });
        </script>
        <script>
            function sweetAlertDelete(event, formId) {
                event.preventDefault();
                let form = document.getElementById(formId);
                Swal.fire({
                    title: '@lang('Are you sure ? ')',
                    icon : 'question',
                    showDenyButton: true,
                    confirmButtonText: '@lang('Delete ')',
                    denyButtonText: '@lang(' Cancel ')',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            }
        </script>
        <script>
            document.getElementById('filterToggle').addEventListener('click', function() {
                var filterForm = document.getElementById('filterForm');
                filterForm.classList.toggle('hidden');
            });

            // // Open Modal
            // document.getElementById('openValidateVoucherModal').addEventListener('click', function() {
            //     document.getElementById('validateModal').classList.remove('hidden');
            // });

            // // Close Modal
            // document.getElementById('closeValidateVoucherModal').addEventListener('click', function() {
            //     document.getElementById('validateModal').classList.add('hidden');
            // });
        </script>

        {{-- <script>
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
                                document.getElementById('patient_id').value = data.patient_id || '';
                                document.getElementById('paket_voucher_name').value = data.paket_voucher_name || '';
                                document.getElementById('expiry_date').value = data.expiry_date || '';
                                document.getElementById('purchase_date').value = data.purchase_date || '';

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
        </script> --}}
    @endpush
</x-app-layout>
