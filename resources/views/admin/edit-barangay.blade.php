<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Edit Barangay</h2>
                <a href="{{ route('barangay.load') }}" 
                   class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors duration-200">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>
            </div>

            <form action="{{ route('barangays.update', $barangay) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="barangay_name" class="block text-sm font-medium text-gray-700">Barangay Name</label>
                        <input type="text" 
                               name="barangay_name" 
                               id="barangay_name" 
                               value="{{ old('barangay_name', $barangay->barangay_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                               required>
                        @error('barangay_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-green-600 text-white hover:bg-green-700 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors duration-200">
                            <i class="fas fa-save"></i>
                            <span>Update Barangay</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 