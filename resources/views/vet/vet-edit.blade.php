<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Title Section with gradient header -->
            <div class="bg-gradient-to-r from-green-800 to-green-600 p-6 text-white">
                <h2 class="text-3xl font-bold">Edit Veterinarian</h2>
                <p class="text-blue-100 mt-2">Update veterinarian details below.</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="m-6 bg-red-100 text-red-700 p-4 rounded-lg shadow-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Veterinarian Edit Form -->
            <form action="{{ route('newvets.update', $veterinarian->user_id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <!-- Current Profile Image -->
                <div class="mb-6 text-center">
                    @if ($veterinarian->profile_image)
                        <img class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-green-500 shadow-lg hover:scale-105 transition-transform duration-300" src="{{ Storage::url($veterinarian->profile_image) }}" alt="Current Profile Image">
                    @else
                        <div class="w-32 h-32 rounded-full mx-auto bg-gray-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 mt-2">No profile image uploaded</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Image Upload -->
                    <div class="md:col-span-2">
                        <x-input-label for="profile_image" :value="__('Profile Image')" class="text-lg font-semibold text-gray-800"/>
                        <div class="mt-1 flex items-center">
                            <label for="profile_image" class="w-full flex items-center justify-center px-4 py-2 bg-white text-blue-500 rounded-lg shadow-sm border border-blue-300 cursor-pointer hover:bg-blue-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                Choose Profile Image
                                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="hidden">
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Upload a profile image (JPEG/PNG, max: 2MB).</p>
                    </div>

                    <!-- Complete Name -->
                    <div>
                        <x-input-label for="complete_name" :value="__('Complete Name')" class="text-lg font-semibold text-gray-800"/>
                        <x-text-input name="complete_name" id="complete_name" value="{{ old('complete_name', $veterinarian->complete_name) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                    </div>

                    <!-- Contact No. -->
                    <div>
                        <x-input-label for="contact_no" :value="__('Contact No.')" class="text-lg font-semibold text-gray-800"/>
                        <x-text-input name="contact_no" id="contact_no" value="{{ old('contact_no', $veterinarian->contact_no) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                    </div>

                    <!-- Gender -->
                    <div>
                        <x-input-label for="gender" :value="__('Gender')" class="text-lg font-semibold text-gray-800"/>
                        <select name="gender" id="gender" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                            <option value="Male" {{ old('gender', $veterinarian->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $veterinarian->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <x-input-label for="birth_date" :value="__('Birth Date')" class="text-lg font-semibold text-gray-800"/>
                        <x-text-input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $veterinarian->birth_date->toDateString()) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" required />
                    </div>

                    <!-- Designation -->
                    <div>
                        <x-input-label for="designation_id" :value="__('Designation')" class="text-lg font-semibold text-gray-800"/>
                        <select name="designation_id" id="designation_id" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                            @foreach ($designations as $designation)
                                <option value="{{ $designation->designation_id }}" {{ old('designation_id', $veterinarian->designation_id) == $designation->designation_id ? 'selected' : '' }}>
                                    {{ $designation->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Barangay -->
                    <div>
                        <x-input-label for="barangay_id" :value="__('Barangay')" class="text-lg font-semibold text-gray-800"/>
                        <select name="barangay_id" id="barangay_id" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500">
                            @foreach ($barangays as $barangay)
                                <option value="{{ $barangay->id }}" {{ old('barangay_id', $veterinarian->address->barangay_id) == $barangay->id ? 'selected' : '' }}>
                                    {{ $barangay->barangay_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="street" :value="__('Street')" class="text-lg font-semibold text-gray-800"/>
                        <x-text-input type="text" name="street" id="street" value="{{ old('street', $veterinarian->address->street) }}" class="mt-1 block w-full rounded-lg border-green-600 shadow-sm focus:ring-2 focus:ring-green-500" />
                    </div>
                </div>

                <div class="flex items-center justify-between mt-8">
                    <button type="button" onclick="window.history.back()" class="inline-flex items-center px-5 py-3 bg-gray-500 text-white rounded-lg shadow-md hover:bg-gray-600 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Cancel
                    </button>
                    
                    <x-primary-button class="inline-flex items-center bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 focus:outline-none text-white font-semibold rounded-lg px-6 py-3 shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Update Veterinarian') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>