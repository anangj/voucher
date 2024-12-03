<x-app-layout>
    <div class="max-w-6xl mx-auto">
        {{-- Error and success message alerts --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        {{-- Alert Messages --}}
        @if (session('message'))
            <x-alert :message="session('message')" :type="'success'" />
        @endif
        
        <div class="flex justify-between">
            <form action="{{ route('vouchers.export-receipt') }}" method="POST">
                @csrf
                
                <input type="hidden" id="package_name" name="package_name" value="{{ $data->paketVoucher->name }}">
                <input type="hidden" id="purchase_date" name="purchase_date" value="{{ $data->purchase_date }}">
                <input type="hidden" id="expiry_date" name="expiry_date" value="{{ $data->expiry_date }}">
                <input type="hidden" id="patient_id" name="patient_id" value="{{ $data->patient->id }}">
                <input type="hidden" id="paket_voucher_id" name="paket_voucher_id" value="{{ $data->paketVoucher->id }}">
                <input type="hidden" id="voucher_price" name="voucher_price" value="Rp {{ number_format($data->paketVoucher->amount, 0, ',', '.') }}">
                <input type="hidden" id="payment_method" name="payment_method" value="{{ $data->payment->payment_method}}">
                <input type="hidden" id="no_card" name="no_card" value="{{ $data->payment->no_card}}">
                

                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">
                    {{ __('Export to PDF') }}
                </button>
            </form>
            
            <div class="flex justify-end mb-4">
                <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2 !px-3" href="{{ route('vouchers.index') }}">
                    <iconify-icon class="mr-1 text-lg" icon="ic:outline-arrow-back"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('Nama Paket:') }}</label>
                        <input type="text" value="{{ $data->paketVoucher->name }}" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('No. Voucher:') }}</label>
                        <input type="text" value="{{$data->voucher_header_no}}" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('Digunakan oleh:') }}</label>
                        {{-- <p class="text-gray-600 text-sm">{{ __('[lookup list nama pasien terdaftar, beserta tgl transaksi voucher]') }}</p> --}}
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('Tanggal Pembelian:') }}</label>
                        <input type="text" value="{{ $data->purchase_date }}" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold block">{{ __('Masa Berlaku:') }}</label>
                        <input type="text" value="{{ $data->expiry_date }}" class="form-control w-full bg-gray-100 border border-gray-300 rounded-md" readonly>
                    </div>

                    {{-- <div class="mb-4">
                        <label class="font-semibold block">{{ __('Sisa Voucher:') }}</label>
                        <input type="text" value="{{ $remainingUses }}" class="form-input w-12 bg-red-100 border border-red-300 rounded-md text-center text-red-600" readonly>
                    </div> --}}
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            {{-- <th class="border border-gray-300 px-4 py-2">{{ __('No.') }}</th> --}}
                            <th class="border border-gray-300 px-4 py-2">{{ __('Nama Pasien') }}</th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('Nama Keluarga') }}</th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('Tanggal Lahir') }}</th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('No. Telepon') }}</th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('No Voucher Terpakai') }}</th>
                            <th class="border border-gray-300 px-4 py-2">{{ __('Tanggal Penggunaan Voucher') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        {{-- Example rows, replace with dynamic data --}}
                        @foreach ($tables as $item)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">
                                    @if ($item->issued_to_family_member === 1)
                                        {{ $data->patient->familyMember->first()->name }}  {{-- Use family member's name --}}
                                    @else
                                        {{ $data->patient->name }}  {{-- Use patient's name --}}
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-4 py-2">{{ $data->patient->familyMember ? $data->patient->familyMember->first()->name : '' }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    @if ($item->issued_to_family_member === 1)
                                        {{ $data->patient->familyMember->first()->birthday }}
                                    @else
                                        {{ $data->patient->birthday }}
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    @if ($item->issued_to_family_member === 1)
                                        {{ $data->patient->familyMember->first()->phone }}
                                    @else
                                        {{ $data->patient->phone }}
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->voucher_no }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $item->using_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
