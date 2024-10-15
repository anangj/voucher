<x-app-layout>
    <div class="max-w-xl mx-auto p-6 bg-white shadow-md rounded-md">
        <h2 class="text-lg font-bold mb-4">{{ __('Edit Patient') }}</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form action="{{ route('patient.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Patient Information -->
            <div class="form-group mb-4">
                <label for="name" class="block text-gray-700">{{ __('Name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name', $patient->name) }}" class="form-control w-full p-2 border border-gray-300 rounded-md" required>
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="form-group mb-4">
                <label for="birthday" class="block text-gray-700">{{ __('Birthday') }}</label>
                <input type="date" id="birthday" name="birthday" value="{{ old('birthday', $patient->birthday) }}" class="form-control w-full p-2 border border-gray-300 rounded-md" required>
                <x-input-error :messages="$errors->get('birthday')" />
            </div>

            <div class="form-group mb-4">
                <label for="phone" class="block text-gray-700">{{ __('Phone') }}</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $patient->phone) }}" class="form-control w-full p-2 border border-gray-300 rounded-md" required>
                <x-input-error :messages="$errors->get('phone')" />
            </div>

            <div class="form-group mb-4">
                <label for="email" class="block text-gray-700">{{ __('Email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email', $patient->email) }}" class="form-control w-full p-2 border border-gray-300 rounded-md" required>
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <!-- Family Member Section (Single Entry) -->
            <h3 class="text-lg font-bold mt-6 mb-4">{{ __('Family Member') }}</h3>

            <div class="family-member mb-4 p-4 border border-gray-300 rounded-md">
                <div class="form-group mb-4">
                    <label for="family_name" class="block text-gray-700">{{ __('Name') }}</label>
                    <input type="text" name="family_name" id="family_name" value="{{ old('family_name', $patient->familyMember->name ?? '') }}" class="form-control w-full p-2 border border-gray-300 rounded-md">
                    <x-input-error :messages="$errors->get('family_name')" />
                </div>

                <div class="form-group mb-4">
                    <label for="family_birthday" class="block text-gray-700">{{ __('Birthday') }}</label>
                    <input type="date" name="family_birthday" id="family_birthday" value="{{ old('family_birthday', $patient->familyMember->birthday ?? '') }}" class="form-control w-full p-2 border border-gray-300 rounded-md">
                    <x-input-error :messages="$errors->get('family_birthday')" />
                </div>

                <div class="form-group mb-4">
                    <label for="family_phone" class="block text-gray-700">{{ __('Phone') }}</label>
                    <input type="text" name="family_phone" id="family_phone" value="{{ old('family_phone', $patient->familyMember->phone ?? '') }}" class="form-control w-full p-2 border border-gray-300 rounded-md">
                    <x-input-error :messages="$errors->get('family_phone')" />
                </div>

                <div class="form-group mb-4">
                    <label for="family_email" class="block text-gray-700">{{ __('Email') }}</label>
                    <input type="email" name="family_email" id="family_email" value="{{ old('family_email', $patient->familyMember->email ?? '') }}" class="form-control w-full p-2 border border-gray-300 rounded-md">
                    <x-input-error :messages="$errors->get('family_email')" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                    {{ __('Update Patient') }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
