<x-app-layout>
    <div>
        <div class="mb-6">
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />
        </div>

        {{-- Alert start --}}
        @if (session('message'))
            <x-alert :message="session('message')" :type="'success'" />
        @endif
        {{-- Alert end --}}

        <div class="card">
            <div class="flex justify-between">
                <div class="card-header noborder">
                    <button type="button" id="createVoucherBtn" class="btn inline-flex justify-center btn-outline-dark capitalize" disabled>
                        {{ __('Create Voucher') }}
                    </button>
                </div>
                {{-- Trigger Modal Button --}}
                <div class="card-header noborder">
                    <button id="openSearchPatientModal" class="btn inline-flex justify-center btn-outline-dark capitalize">
                        {{ __('Search Patient') }}
                    </button>
                </div>
            </div>


            <div class="card-body px-6 pb-6">
                <div class="-mx-6 overflow-x-auto dashcode-data-table">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <form id="bulkActionForm" method="post" action="{{ route('vouchers.create') }}">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="selected_patients" id="selected_patients" value="">

                                <table class="w-full divide-y table-fixed divide-slate-100 dark:divide-slate-700 data-table">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class="table-th">
                                            </th>
                                            <th scope="col" class="table-th">{{ __('No RM') }}</th>
                                            <th scope="col" class="table-th">{{ __('No Regis') }}</th>
                                            <th scope="col" class="table-th">{{ __('Patient Name') }}</th>
                                            <th scope="col" class="table-th">{{ __('Birthday') }}</th>
                                            <th scope="col" class="table-th">{{ __('Phone') }}</th>
                                            <th scope="col" class="table-th">{{ __('Email') }}</th>
                                            <th scope="col" class="table-th">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 dark:divide-slate-700 dark:bg-slate-800">
                                        @forelse ($data as $item)
                                            <tr>
                                                <td class="table-td">
                                                    <input type="checkbox" class="select-item" value="{{ $item->id }}">
                                                </td>
                                                <td class="table-td">{{ $item->rm_no }}</td>
                                                <td class="table-td">{{ $item->registration_no }}</td>
                                                <td class="table-td">{{ $item->name }}</td>
                                                <td class="table-td">{{ $item->birthday }}</td>
                                                <td class="table-td">{{ $item->phone }}</td>
                                                <td class="table-td">{{ $item->email }}</td>
                                                <td class="table-td">
                                                    <div class="flex space-x-1">
                                                        @can('patient update')
                                                        <a href="{{ route('patient.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                                            <iconify-icon icon="mdi:pencil-outline" class="text-xl"></iconify-icon>
                                                        </a>
                                                        @endcan

                                                        {{-- <form action="{{ route('patient.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this voucher?') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <iconify-icon icon="mdi:delete-outline" class="text-xl"></iconify-icon>
                                                            </button>
                                                        </form> --}}

                                                        {{-- @can('patient delete')
                                                            <form action="{{ route('patient.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this voucher?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <iconify-icon icon="mdi:delete-outline" class="text-xl"></iconify-icon>
                                                                </button>
                                                            </form>
                                                        @endcan --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="relative border border-slate-100 dark:border-slate-900">
                                                <td class="table-cell text-center" colspan="7">
                                                    <img src="images/result-not-found.svg" alt="No results found" class="w-64 m-auto" />
                                                    <h2 class="mb-8 -mt-4 text-xl text-slate-700">{{ __('No results found.') }}</h2>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Patient Modal --}}
        <div id="searchPatientModal" class="hidden fixed top-0 left-0 z-50 w-full h-full bg-gray-800 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-lg font-bold mb-4">{{ __('Search Patient') }}</h3>

                <form id="searchPatientForm" class="space-y-3">
                    {{-- RM No --}}
                    <input type="text" id="rm_no" name="rm_no" placeholder="Enter RM No" class="form-control" autocomplete="off">

                    {{-- Name --}}
                    <input type="text" id="name" name="name" placeholder="Enter Name" class="form-control" autocomplete="off">

                    {{-- BOD (Birthdate) --}}
                    <input type="date" id="bod" name="bod" placeholder="Enter Birthdate" class="form-control">

                    {{-- Address (Alamat) --}}
                    <input type="text" id="alamat" name="alamat" placeholder="Enter Address" class="form-control" autocomplete="off">

                    <div class="flex justify-between mt-4">
                        <button type="button" id="searchPatientBtn" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                            {{ __('Search') }}
                        </button>
                        <button type="button" id="closeSearchPatientModal" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">
                            {{ __('Close') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>

            // Handle Create Voucher Button click
            document.getElementById('createVoucherBtn').addEventListener('click', function() {
                const selectedPatients = [];
                document.querySelectorAll('.select-item:checked').forEach(checkbox => {
                    selectedPatients.push(checkbox.value);
                });

                if (selectedPatients.length === 0) {
                    alert('No patients selected.');
                    return;
                }

                // Set the value of the hidden input field
                document.getElementById('selected_patients').value = JSON.stringify(selectedPatients);

                // Submit the form
                document.getElementById('bulkActionForm').submit();
            });

            // // Open Modal
            // document.getElementById('openSearchPatientModal').addEventListener('click', function() {
            //     document.getElementById('searchPatientModal').classList.remove('hidden');
            // });

            // // Close Modal
            // document.getElementById('closeSearchPatientModal').addEventListener('click', function() {
            //     document.getElementById('searchPatientModal').classList.add('hidden');
            // });

            // document.getElementById('searchPatientBtn').addEventListener('click', function() {
            //     const rmNo = document.getElementById('rm_no').value;
            //     const name = document.getElementById('name').value;   // Get Name value
            //     const bod = document.getElementById('bod').value;     // Get Birthdate value
            //     const alamat = document.getElementById('alamat').value;  // Get Address value

            //     // Disable button during the request
            //     const searchPatientBtn = document.getElementById('searchPatientBtn');
            //     searchPatientBtn.disabled = true;


            //     // Example API parameters and body data
            //     const apiUrl = 'https://api.example.com/search-patient'; // Replace with the actual API endpoint
            //     const apiToken = 'your-api-token';  // Replace with your API token
            //     const searchParams = {
            //         rm_no: rmNo || null,  // Pass rm_no if available
            //         name: name || null,   // Pass name if available
            //         bod: bod || null,     // Pass birthdate if available
            //         alamat: alamat || null // Pass address if available
            //     };

            //     // Send API request with simple auth (Bearer token)
            //     fetch(apiUrl, {
            //         method: 'POST',
            //         headers: {
            //             'Authorization': `Bearer ${apiToken}`,  // Use Bearer token for simple auth
            //             'Content-Type': 'application/json'
            //         },
            //         body: JSON.stringify(searchParams) // Body payload with rm_no
            //     })
            //     .then(response => response.json())
            //     .then(data => {
            //         // Re-enable the button after fetching the data
            //         searchPatientBtn.disabled = false;

            //         // If data is received, update the table body with the new patients
            //         console.log(data);
            //     })
            //     .catch(error => {
            //         console.error('Error fetching patient data:', error);
            //         alert('Error fetching patient data.');
            //         searchPatientBtn.disabled = false;
            //     });
            // });

            // Handle individual checkbox selection for single selection
            document.querySelectorAll('.select-item').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    // Deselect all other checkboxes
                    document.querySelectorAll('.select-item').forEach(otherCheckbox => {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                    toggleCreateVoucherBtn();  // Update the create button state
                });
            });

            function toggleCreateVoucherBtn() {
                const selectedItems = document.querySelectorAll('.select-item:checked').length;
                document.getElementById('createVoucherBtn').disabled = selectedItems === 0;
            }


        </script>
    @endpush
</x-app-layout>
