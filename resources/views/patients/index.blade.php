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
                <div class="flex">
                    <div class="card-header noborder">
                        <button type="button" id="createVoucherBtn" class="btn inline-flex justify-center btn-outline-dark capitalize" disabled>
                            {{ __('Create Voucher') }}
                        </button>
                    </div>
                    <div class="card-header noborder">
                        <button type="button" id="savePatient" class="btn inline-flex justify-center btn-outline-dark capitalize" style="display: none">
                            {{ __('Save') }}
                        </button>
                    </div>
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
                                    <tbody id="existingPatient" class="bg-white divide-y divide-slate-100 dark:divide-slate-700 dark:bg-slate-800">
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
                    {{-- <input type="text" id="rm_no" name="rm_no" placeholder="Enter RM No" class="form-control" autocomplete="off"> --}}

                    {{-- Name --}}
                    <input type="text" id="name" name="name" placeholder="Enter Name" class="form-control" autocomplete="off">

                    {{-- BOD (Birthdate) --}}
                    <input type="date" id="bod" name="bod" placeholder="Enter Birthdate" class="form-control">

                    {{-- Address (Alamat) --}}
                    {{-- <input type="text" id="alamat" name="alamat" placeholder="Enter Address" class="form-control" autocomplete="off"> --}}

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
            const selectedPatients = [];

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

                const selectedPatientsString = selectedPatients.join(',');

                // Set the value of the hidden input field
                // document.getElementById('selected_patients').value = JSON.stringify(selectedPatients);
                document.getElementById('selected_patients').value = selectedPatientsString;

                // Submit the form
                document.getElementById('bulkActionForm').submit();
            });

             // Open Modal
             document.getElementById('openSearchPatientModal').addEventListener('click', function() {
                 document.getElementById('searchPatientModal').classList.remove('hidden');
             });

            // Close Modal
            document.getElementById('closeSearchPatientModal').addEventListener('click', function() {
                document.getElementById('searchPatientModal').classList.add('hidden');
            });

            // Search Operation
            document.getElementById('searchPatientBtn').addEventListener('click', function () {
                // Gather input values
                // const rmNo = document.getElementById('rm_no').value;
                const name = document.getElementById('name').value;
                const bod = document.getElementById('bod').value;

                // Disable button during the request
                const searchPatientBtn = document.getElementById('searchPatientBtn');
                searchPatientBtn.disabled = true;

                // Make AJAX call to the Laravel controller's searchPatient method
                fetch("{{ route('search.patient') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({name: name, bod: bod })
                })
                .then(response => response.json())
                .then(data => {
                    // Re-enable the button after fetching the data
                    searchPatientBtn.disabled = false;
                    const totalPatient = data.patients.count;
                    
                    if (data.status === 'success' && data.patients.data !== null) {
                        // Hide the existing table (replace with the ID or class of your current table)
                        document.querySelector('.data-table').style.display = 'none';
                        document.getElementById('createVoucherBtn').style.display = 'none';
                        document.getElementById('savePatient').style.display = 'inline-flex';

                        // Create a new table element
                        const newTable = document.createElement('table');
                        newTable.className = 'new-data-table w-full divide-y table-fixed divide-slate-100 dark:divide-slate-700';
                        
                        // Create table header
                        newTable.innerHTML = `
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="table-th">RM</th>
                                    <th scope="col" class="table-th">Patient Name</th>
                                    <th scope="col" class="table-th">Birthdate</th>
                                    <th scope="col" class="table-th">Phone</th>
                                    <th scope="col" class="table-th">Email</th>
                                    <th scope="col" class="table-th">Gender</th>
                                    <th scope="col" class="table-th">Last Visit Date</th>
                                    <th scope="col" class="table-th">Action</th>
                                </tr>
                            </thead>
                        `;

                        // Create table body
                        const tableBody = document.createElement('tbody');
                        tableBody.className = 'bg-white divide-y divide-slate-100 dark:divide-slate-700 dark:bg-slate-800';

                        // Check if data.patients.data is an array or an object
                        const patients = Array.isArray(data.patients.data) ? data.patients.data : [data.patients.data];
                        patients.forEach((patient, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class='table-td'>${patient.no_rm}</td>
                                <td class='table-td'>${patient.title} ${patient.name_real}</td>
                                <td class='table-td'>${patient.tgl_lahir}</td>
                                <td class='table-td'>${patient.hp_pasien}</td>
                                <td class='table-td'>${patient.email}</td>
                                <td class='table-td'>${patient.sex}</td>
                                <td class='table-td'>${patient.tgl_kunjungan_terakhir || '-'}</td>
                                <td class='table-td'>
                                    <div class="flex space-x-1">
                                        <button class="btn btn-sm btn-warning select-patient" data-index="${index}">
                                            Select
                                        </button>
                                    </div>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                        
                        

                        // Append body to the new table
                        newTable.appendChild(tableBody);

                        // Append new table to the DOM
                        document.querySelector('.card-body').appendChild(newTable);
                        document.getElementById('searchPatientModal').classList.add('hidden');
                        document.getElementById('name').value = '';
                        document.getElementById('bod').value = '';
                        
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching patient data:', error);
                    alert('Error fetching patient data.');
                    searchPatientBtn.disabled = false;
                });
            });

            // Select the Searching patient
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('select-patient')) {
                    event.preventDefault();

                    // Retrieve the patient data from the table row
                    const row = event.target.closest('tr');
                    const patientData = {
                        no_rm: row.querySelector('td:nth-child(1)').innerText,
                        name_real: row.querySelector('td:nth-child(2)').innerText,
                        tgl_lahir: row.querySelector('td:nth-child(3)').innerText,
                        phone: row.querySelector('td:nth-child(4)').innerText,
                        email: row.querySelector('td:nth-child(5)').innerText,
                        sex: row.querySelector('td:nth-child(6)').innerText,
                        tgl_kunjungan_terakhir: row.querySelector('td:nth-child(7)').innerText,
                    };

                    // Check if the patient is already selected
                    const patientIndex = selectedPatients.findIndex(
                        (p) => p.name_real === patientData.name_real && p.tgl_lahir === patientData.tgl_lahir
                    );

                    if (patientIndex === -1) {
                        // Patient is not selected, so add to selectedPatients
                        selectedPatients.push(patientData);
                        row.style.backgroundColor = '#e0f7fa'; // Highlight row
                        event.target.textContent = 'Unselect'; // Change button label
                    } else {
                        // Patient is already selected, so remove from selectedPatients
                        selectedPatients.splice(patientIndex, 1);
                        row.style.backgroundColor = ''; // Remove highlight
                        event.target.textContent = 'Select'; // Change button label
                    }

                    console.log('Current selected patients:', selectedPatients);
                    document.getElementById('savePatient').addEventListener('click', submitSelectedPatients);
                }
            });

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

            function submitSelectedPatients() {
                console.log('sasa', selectedPatients);
                
                if (selectedPatients.length === 0) {
                    alert("No patients selected.");
                    return;
                }

                // Send selected patients to the server
                fetch("{{ route('patient.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ patients: selectedPatients })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        console.log('Selected Patient IDs:', data.selected_patients); 
                        alert('Patients saved successfully.');
                        window.location.href = "{{ route('patient.index') }}";

                        // Call createVoucher with the selected patient IDs
                        // createVoucher(data.selected_patients);
                    } else {
                        alert('An error occurred while saving patients.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving patients.');
                });
            }

            function createVoucher(selectedPatients) {
                fetch("{{ route('vouchers.create') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ selected_patients: selectedPatients })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Voucher created successfully!');
                        // Optionally, handle success (e.g., update the UI or navigate to another page)
                        window.location.href = "{{ route('vouchers.create') }}";
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while creating the voucher.');
                });
            }



        </script>
    @endpush
</x-app-layout>
