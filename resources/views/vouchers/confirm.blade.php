<x-app-layout>
    <div class="max-w-5xl mx-auto">
        {{-- Title --}}
        <h1 class="text-2xl font-bold mb-6">{{ __('Konfirmasi Pembelian') }}</h1>

        {{-- Alert Messages --}}
        @if(session('message'))
            <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-md">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        {{-- Confirmation Form --}}
        <div class="bg-white p-6 rounded-md shadow-md">
            <form action="{{route('vouchers.store')}}" method="POST" id="confirmationForm">
                @csrf
                {{-- Package Voucher Information --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    {{-- Package Name --}}
                    <div>
                        <label for="package_name" class="font-bold">{{ __('Nama Paket :') }}</label>
                        <input type="text" id="package_name" name="package_name" value="{{$dataPaket[0]['name']}}" class="form-control w-full" readonly>
                    </div>
                    {{-- Voucher Number --}}
                    <div>
                        <label for="voucher_header_no" class="font-bold">{{ __('No. Voucher :') }}</label>
                        <input type="text" id="voucher_header_no" name="voucher_header_no" value="{{$voucher_header_no}}" class="form-control w-full" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    {{-- Purchase Date --}}
                    <div>
                        <label for="purchase_date" class="font-bold">{{ __('Tanggal Pembelian :') }}</label>
                        <input type="text" id="purchase_date" name="purchase_date" value="{{ $purchase_date }}" class="form-control w-full" readonly>
                    </div>
                    {{-- Expiry Date --}}
                    <div>
                        <label for="expiry_date" class="font-bold">{{ __('Masa Berlaku :') }}</label>
                        <input type="text" id="expiry_date" name="expiry_date" value="{{ $expiry_date }}" class="form-control w-full" readonly>
                    </div>
                </div>

                <input type="hidden" id="merged_data_input" name="merged_data" value="">
                <input type="hidden" id="patient_id" name="patient_id" value="{{$patients[0]['id']}}">
                <input type="hidden" id="paket_voucher_id" name="paket_voucher_id" value="{{$dataPaket[0]['id']}}">

                {{-- List of Patients or Family Members --}}
                <div class="mb-4">
                    <label class="font-bold">{{ __('Digunakan Oleh:') }}</label>
                    <table class="w-full mt-2">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border px-2 py-1">{{ __('No.') }}</th>
                                <th class="border px-2 py-1">{{ __('Nama') }}</th>
                                <th class="border px-2 py-1">{{ __('Tanggal Lahir') }}</th>
                                <th class="border px-2 py-1">{{ __('No. Telepon') }}</th>
                                <th class="border px-2 py-1">{{ __('Email') }}</th>
                                <th class="border px-2 py-1">{{ __('Type') }}</th>
                            </tr>
                        </thead>
                        <tbody id="patientsTable">
                            @foreach ($patients as $index => $patient)
                                {{-- Display the main patient --}}
                                <tr>
                                    <td class="border px-2 py-1">{{ $index + 1 }}</td>
                                    <td class="border px-2 py-1">{{ $patient['name'] }}</td>
                                    <td class="border px-2 py-1">{{ $patient['birthday'] }}</td>
                                    <td class="border px-2 py-1">{{ $patient['phone'] }}</td>
                                    <td class="border px-2 py-1">{{ $patient['email'] }}</td>
                                    <td class="border px-2 py-1">Patient</td>
                                </tr>

                                {{-- Display each family member (if any) under the patient --}}
                                @if (!empty($patient['family_members']))
                                    @foreach ($patient['family_members'] as $familyIndex => $familyMember)
                                        <tr>
                                            <td class="border px-2 py-1">{{ $index + 1 }}.{{ $familyIndex + 1 }}</td>
                                            <td class="border px-2 py-1">{{ $familyMember['name'] }}</td>
                                            <td class="border px-2 py-1">{{ $familyMember['birthday'] }}</td>
                                            <td class="border px-2 py-1">{{ $familyMember['phone'] }}</td>
                                            <td class="border px-2 py-1">{{ $familyMember['email'] }}</td>
                                            <td class="border px-2 py-1">Family Member</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Voucher Price --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    {{-- Voucher Price --}}
                    <div>
                        <label for="voucher_price" class="font-bold">{{ __('Harga Voucher :') }}</label>
                        <input type="text" id="voucher_price" name="voucher_price"
                               value="Rp {{ number_format($dataPaket[0]['amount'], 0, ',', '.') }}"
                               class="form-control w-full" readonly>
                    </div>
                    {{-- Payment Method --}}
                    <div>
                        <label for="payment_method" class="font-bold">{{ __('Metode Pembayaran :') }}</label>
                        <select id="payment_method" name="payment_method" class="form-control w-full">
                            <option value="Credit">{{ __('Credit Card') }}</option>
                            <option value="Debit">{{ __('Debit Card') }}</option>
                            <option value="Cash">{{ __('Cash') }}</option>
                        </select>
                    </div>
                </div>

                {{-- Submit and Print Receipt Button --}}
                <div class="flex justify-between mt-4">
                    {{-- Save Button --}}
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600" id="submitForm">
                        {{ __('Simpan') }}
                    </button>

                    {{-- Export to PDF Button --}}
                    {{-- <button type="button" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600" id="exportPdfBtn">
                        {{ __('Export to PDF') }}
                    </button> --}}

                    {{-- Print Receipt Button (conditional, shown after successful save) --}}
                    {{-- <button type="button" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600" onclick="printReceipt()" id="printReceiptBtn">
                        {{ __('Cetak Bukti Bayar') }}
                    </button> --}}
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Handle form submission
                document.getElementById('submitForm').addEventListener('click', function (e) {
                    e.preventDefault();

                    // Store the merged patients and family members into the hidden input field as JSON
                    const mergedData = @json($patients);  // This contains the patient and family data passed from backend
                    const mergedDataInput = document.getElementById('merged_data_input');
                    mergedDataInput.value = JSON.stringify(mergedData);

                    // Submit the form
                    e.target.closest('form').submit();
                });

                // Handle form submission and export to PDF
                // document.getElementById('exportPdfBtn').addEventListener('click', function () {
                //     const form = document.getElementById('confirmationForm');
                //     const action = "{{ route('vouchers.export-receipt') }}";

                //     // Change the form action to export the PDF
                //     form.action = action;
                //     form.method = 'POST';
                //     form.submit();
                // });

                // Optional: Enable Print Button if successful save
                // const printReceiptButton = document.getElementById('printReceiptBtn');
                // Enable it after successful response from backend
                // printReceiptButton.disabled = false;
            });

            function printReceipt() {
                window.print();
            }
        </script>
    @endpush
</x-app-layout>
