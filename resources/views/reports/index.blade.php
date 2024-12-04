<x-app-layout>
    <div>
        <div class="mb-6 ">
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />
        </div>

        <!-- Filter Notification -->
        @if (request()->filled('name') || request()->filled('date') || request()->filled('status'))
            <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded-md">
                <p><strong>Notice:</strong> The results are currently filtered.</p>
                <a href="{{ route('reports.index') }}" class="text-blue-500 underline">Clear Filters</a>
            </div>
        @endif

        {{-- Alert start --}}
        @if (session('message'))
        <x-alert :message="session('message')" :type="'success'" />
        @endif
        {{-- Alert end --}}

        <div class="card">
            <div class="flex justify-end">
                <div class="card-header noborder">
                    <div class="flex flex-wrap items-center justify-end">
                        <div>
                            <a href="{{ route('export.vouchers') }}" class="inline-flex justify-center btn btn-dark rounded-[25px] items-center !p-2 !px-3">Export to Excel</a>
                            {{-- <button id="export" class="inline-flex justify-center btn btn-dark rounded-[25px] items-center !p-2 !px-3">Export</button> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-6 pb-6">
                <div class="-mx-6 overflow-x-auto dashcode-data-table">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="w-full divide-y table-fixed divide-slate-100 dark:divide-slate-700 data-table">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>
                                        {{-- <th scope="col" class="table-th"> {{__('No')}} </th> --}}
                                        {{-- <th scope="col" class="table-th"> {{ __('Invoice') }} </th> --}}
                                        <th scope="col" class="table-th"> {{__('Patient Name')}} </th>
                                        <th scope="col" class="table-th"> {{__('Voucher Name')}} </th>
                                        <th scope="col" class="table-th"> {{__('Price')}} </th>
                                        <th scope="col" class="table-th"> {{__('Payment Date')}} </th>
                                        <th scope="col" class="table-th"> {{__('Payment Method')}} </th>
                                        <th scope="col" class="table-th"> {{__('Note')}} </th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-slate-100 dark:divide-slate-700 dark:bg-slate-800">
                                    @foreach ($voucherSales as $key => $sale)
                                        <tr>
                                            {{-- <td class="px-6 py-4">{{ $sale->invoice_number }}</td> --}}
                                            <td class="px-6 py-4">{{ $sale->patient->name }}</td>
                                            <td class="px-6 py-4">{{ $sale->voucherHeader->paketVoucher->name }}</td>
                                            <td class="px-6 py-4">{{ number_format($sale->amount) }}</td>
                                            <td class="px-6 py-4">{{ $sale->created_at->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4">{{ $sale->payment_method }}</td>
                                            <td class="px-6 py-4">{{ $sale->no_card }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
    @endpush
</x-app-layout>