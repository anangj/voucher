<x-app-layout>
    <div>
        <div class="items-center justify-between block mb-6 sm:flex">
            <x-breadcrumb :pageTitle="$pageTitle" :breadcrumbItems="$breadcrumbItems" />
            <div class="text-end">
                <a class="btn inline-flex justify-center btn-dark rounded-[25px] items-center !p-2 !px-3" href="{{ route('vouchers.index') }}">
                    <iconify-icon class="mr-1 text-lg" icon="ic:outline-arrow-back"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>

        <form id="voucherForm" action="{{ route('vouchers.confirm') }}" method="POST" class="max-w-4xl m-auto">
            @csrf
            <div class="p-5 pb-6 bg-white rounded-md dark:bg-slate-800">
                <div class="grid sm:grid-cols-1 gap-x-8 gap-y-4">

                    {{-- Select Paket Voucher --}}
                    <div class="form-group">
                        <label for="paket_voucher_id" class="form-label">{{ __('Select Paket Voucher') }}</label>
                        <select name="paket_voucher_id" id="paket_voucher_id" class="form-control" required>
                            <option value="">{{ __('Choose Paket Voucher') }}</option>
                            @foreach ($packageVoucher as $paketVoucher)
                                <option value="{{ $paketVoucher->id }}" data-max-sharing="{{ $paketVoucher->max_sharing }}">
                                    {{ $paketVoucher->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('paket_voucher_id')" class="mt-2" />
                    </div>

                    <div class="form-group">
                        <label for="voucher_header_no" class="form-label">{{ __('Voucher No') }}</label>
                        <input type="text" id="voucher_header_no" name="voucher_header_no" class="form-control" value="{{ $voucherNo ?? '' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="purchase_date" class="form-label">{{ __('Purchase Date') }}</label>
                        <input type="date" id="purchase_date" name="purchase_date" class="form-control" value="{{ old('purchase_date', now()->format('Y-m-d')) }}" required>
                        <x-input-error :messages="$errors->get('purchase_date')" class="mt-2" />
                    </div>

                    {{-- Expiry Date --}}
                    <div class="form-group">
                        <label for="expiry_date" class="form-label">{{ __('Expiry Date') }}</label>
                        <input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required>
                        <x-input-error :messages="$errors->get('expiry_date')" class="mt-2" />
                    </div>

                    {{-- Add Family Button --}}
                    <div class="form-group">
                        <label for="family_name" class="form-label">{{ __('Share With') }}</label>
                        <button type="button" id="addFamilyBtn" class="btn btn-outline-dark" disabled>
                            {{ __('Add Family Member') }}
                        </button>
                    </div>

                    <input type="hidden" name="selected_patients" value="{{ json_encode($patients->pluck('id')) }}">

                    {{-- Patients & Family Members Table --}}
                    <table class="w-full divide-y table-fixed divide-slate-100 dark:divide-slate-700 data-table">
                        <thead class="bg-slate-200 dark:bg-slate-700">
                            <tr>
                                <th scope="col" class="table-th">{{ __('Name') }}</th>
                                <th scope="col" class="table-th">{{ __('Birthday') }}</th>
                                <th scope="col" class="table-th">{{ __('Phone') }}</th>
                                <th scope="col" class="table-th">{{ __('Email') }}</th>
                                <th scope="col" class="table-th">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="patientsTable" class="bg-white divide-y divide-slate-100 dark:divide-slate-700 dark:bg-slate-800">
                            @foreach ($patients as $item)
                                <tr>
                                    <td class="table-td">{{ $item->name }}</td>
                                    <td class="table-td">{{ $item->birthday }}</td>
                                    <td class="table-td">{{ $item->phone }}</td>
                                    <td class="table-td">{{ $item->email }}</td>
                                    <td class="table-td">
                                        {{-- @can('voucher update') --}}
                                        <a type="button" href="{{ route('patient.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                           Edit
                                        </a>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Submit Button --}}
                    <div class="flex justify-end">
                        <button type="submit" id="nextButton" class="btn btn-dark rounded-md !p-2 !px-3">
                            {{ __('Next') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const paketVoucherSelect = document.getElementById('paket_voucher_id');
                const addFamilyBtn = document.getElementById('addFamilyBtn');
                const patientsTable = document.getElementById('patientsTable');
                const nextButton = document.getElementById('nextButton');
                let maxSharing = 0;
                let familyCount = 0;

                // Load family members from localStorage (if any)
                loadFamilyMembersFromLocalStorage();

                // Enable or disable the "Add Family Member" button based on max_sharing
                paketVoucherSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    maxSharing = parseInt(selectedOption.getAttribute('data-max-sharing'), 10) || 0;
                    clearFamilyMembers();
                    updateAddFamilyBtn();
                });

                // Handle Add Family Button click
                addFamilyBtn.addEventListener('click', function () {
                    if (maxSharing > 1) {
                        const familyMember = { name: '', birthday: '', phone: '', email: '' };
                        addFamilyMemberRow(familyMember);
                        familyCount++;
                        updateAddFamilyBtn();
                        saveFamilyMembersToLocalStorage();
                    }
                });

                // Function to add a new row for a family member
                function addFamilyMemberRow(member) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="table-td"><input type="text" class="form-control" name="family_name[]" value="${member.name || ''}" required></td>
                        <td class="table-td"><input type="date" class="form-control" name="family_birthday[]" value="${member.birthday || ''}" required></td>
                        <td class="table-td"><input type="text" class="form-control" name="family_phone[]" value="${member.phone || ''}" required></td>
                        <td class="table-td"><input type="email" class="form-control" name="family_email[]" value="${member.email || ''}" required></td>
                        <td class="table-td"><button type="button" class="btn btn-danger btn-sm deleteFamilyMemberBtn">Delete</button></td>
                    `;
                    patientsTable.appendChild(row);
                    row.querySelector('.deleteFamilyMemberBtn').addEventListener('click', () => deleteFamilyMember(row));
                }

                // Function to delete a family member row
                function deleteFamilyMember(row) {
                    row.remove();
                    familyCount--;
                    updateAddFamilyBtn();
                    saveFamilyMembersToLocalStorage();
                }

                // Update the "Add Family Member" button state
                function updateAddFamilyBtn() {
                    addFamilyBtn.disabled = familyCount >= maxSharing || maxSharing === 0;
                }

                // Save family members to localStorage
                function saveFamilyMembersToLocalStorage() {
                    const familyMembers = [];
                    document.querySelectorAll('#patientsTable tr:not(:nth-child(-n+{{ count($patients) }}))').forEach(row => {
                        const name = row.querySelector('input[name="family_name[]"]').value;
                        const birthday = row.querySelector('input[name="family_birthday[]"]').value;
                        const phone = row.querySelector('input[name="family_phone[]"]').value;
                        const email = row.querySelector('input[name="family_email[]"]').value;
                        familyMembers.push({ name, birthday, phone, email });
                    });
                    localStorage.setItem('familyMembers', JSON.stringify(familyMembers));
                }

                // Load family members from localStorage
                function loadFamilyMembersFromLocalStorage() {
                    const familyMembers = JSON.parse(localStorage.getItem('familyMembers')) || [];
                    familyMembers.forEach(member => addFamilyMemberRow(member));
                    familyCount = familyMembers.length;
                    updateAddFamilyBtn();
                }

                // Clear family members from the table and localStorage
                function clearFamilyMembers() {
                    document.querySelectorAll('#patientsTable tr:not(:nth-child(-n+{{ count($patients) }}))').forEach(row => row.remove());
                    localStorage.removeItem('familyMembers');
                    familyCount = 0;
                    updateAddFamilyBtn();
                }

                // Store data in localStorage when "Next" is clicked
                nextButton.addEventListener('click', function () {
                    const voucherData = {
                        paket_voucher_id: paketVoucherSelect.value,
                        voucher_no: document.getElementById('voucher_no').value,
                        purchase_date: document.getElementById('purchase_date').value,
                        expiry_date: document.getElementById('expiry_date').value,
                        selected_patient: document.getElementById('selected_patient').value,
                        family_members: getFamilyMembersFromTable()
                    };
                    localStorage.setItem('voucherData', JSON.stringify(voucherData));
                });

                // Function to extract family member data from the table
                function getFamilyMembersFromTable() {
                    const familyMembers = [];
                    document.querySelectorAll('#patientsTable tr:not(:nth-child(-n+{{ count($patients) }}))').forEach(row => {
                        const name = row.querySelector('input[name="family_name[]"]').value;
                        const birthday = row.querySelector('input[name="family_birthday[]"]').value;
                        const phone = row.querySelector('input[name="family_phone[]"]').value;
                        const email = row.querySelector('input[name="family_email[]"]').value;
                        familyMembers.push({ name, birthday, phone, email });
                    });
                    return familyMembers;
                }

                // const purchaseDateInput = document.getElementById('purchase_date');
                // const expiryDateInput = document.getElementById('expiry_date');

                // // Set the minimum expiry date initially based on the purchase date
                // expiryDateInput.min = purchaseDateInput.value;

                // // When the purchase date changes, update the minimum expiry date
                // purchaseDateInput.addEventListener('change', function () {
                //     expiryDateInput.min = purchaseDateInput.value;
                // });

                // // Optional: Validate form before submission
                // document.querySelector('form').addEventListener('submit', function (event) {
                //     if (expiryDateInput.value < purchaseDateInput.value) {
                //         event.preventDefault();
                //         alert('The expiry date must be after or equal to the purchase date.');
                //     }
                // });
            });
        </script>
    @endpush
</x-app-layout>
