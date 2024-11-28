<x-app-layout>
    <div class="max-w-6xl mx-auto">

        {{-- Error and success message alerts --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        @if(session('message'))
            <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-md">
                {{ session('message') }}
            </div>
        @endif
        <div class="flex justify-end mb-4">
            <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2 !px-3" href="{{ route('vouchers.index') }}">
                <iconify-icon class="mr-1 text-lg" icon="ic:outline-arrow-back"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">

                {{-- Left column (Voucher Information) --}}
                <div>
                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('Nama Paket:') }}</label>
                        <input type="text" value="{{ $packageName }}" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('No. Voucher:') }}</label>
                        <input type="text" value="{{ $voucherNo }}" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('Digunakan oleh:') }}</label>
                        {{-- <p class="text-gray-600 text-sm">{{ __('[lookup list nama pasien terdaftar, beserta tgl transaksi voucher]') }}</p> --}}
                    </div>
                </div>

                {{-- Right column (Purchase and Voucher Info) --}}
                <div>
                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('Tanggal Pembelian:') }}</label>
                        <input type="text" value="{{ $purchaseDate }}" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('Masa Berlaku:') }}</label>
                        <input type="text" value="{{ $expiryDate }}" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('Sisa Voucher:') }}</label>
                        <input type="text" value="{{ $remainingUses }}" class="form-input w-12 bg-red-100 border border-red-300 rounded-md text-center text-red-600" readonly>
                    </div>
                </div>
            </div>

            {{-- Table: Usage of Vouchers --}}
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            {{-- <th class="border border-gray-300 px-4 py-2">{{ __('No.') }}</th> --}}
                            <th class="border border-gray-300 px-4 py-2">{{ __('Nama') }}</th>
                            {{-- <th class="border border-gray-300 px-4 py-2">{{ __('Tanggal Lahir') }}</th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('No. Telepon') }}</th> --}}
                            <th class="border border-gray-300 px-4 py-2">{{ __('No Voucher Terpakai') }}</th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('Tanggal Penggunaan Voucher') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($tables as $item)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{-- Check if issued to family member or patient --}}
                                    @if ($item->issued_to_family_member)
                                        {{ $item->voucherHeader->patient->familyMember->first()->name ?? 'N/A' }}
                                    @else
                                        {{ $item->voucherHeader->patient->name ?? 'N/A' }}
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ $item->voucher_no }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ $item->using_date ?? 'Not Used' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
