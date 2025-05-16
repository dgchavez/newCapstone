
<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-edit text-green-500 mr-3"></i>Edit Breed
                    </h1>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow mb-6 flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <!-- Breadcrumb Navigation -->
                    <div class="flex items-center text-sm text-gray-500 mb-6">
                        <a href="{{ route('newbreeds.index') }}" class="hover:text-green-600 flex items-center">
                            <i class="fas fa-home mr-2"></i> Breeds Management
                        </a>
                        <span class="mx-2">/</span>
                        <span class="font-medium text-gray-700">Edit Breed: {{ $breed->name }}</span>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                <span class="font-medium">Please fix the following errors:</span>
                            </div>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-6 flex items-center p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-16 w-16 rounded-full 
                                @php
                                    // Use the first 8 characters of MD5 hash converted to integer for the species
                                    $speciesName = $breed->species->name;
                                    $hash = hexdec(substr(md5($speciesName), 0, 8));
                                    $colorIndex = $hash % 6;
                                    
                                    // Array of distinct color classes
                                    $colors = [
                                        'bg-blue-500',
                                        'bg-green-500',
                                        'bg-purple-500',
                                        'bg-red-500',
                                        'bg-yellow-500',
                                        'bg-indigo-500'
                                    ];
                                    
                                    echo $colors[$colorIndex];
                                @endphp 
                                flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($breed->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Editing Breed</h3>
                            <p class="text-sm text-gray-600">
                                You are currently editing "{{ $breed->name }}" of the {{ $breed->species->name }} species. 
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('newbreeds.updates', ['breeds' => $breed->id]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                        <i class="fas fa-tag text-gray-400 mr-1"></i> Breed Name
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            name="name" 
                                            id="name" 
                                            placeholder="Enter breed name"
                                            value="{{ old('name', $breed->name) }}"
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                                            required
                                        >
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-dog text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </p>
                                    @else
                                        <p class="mt-1 text-xs text-gray-500">
                                            <i class="fas fa-info-circle mr-1"></i> Enter a unique breed name (e.g., Golden Retriever, Siamese, etc.)
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="species_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        <i class="fas fa-paw text-gray-400 mr-1"></i> Species
                                    </label>
                                    <div class="relative">
                                        <select 
                                            name="species_id" 
                                            id="species_id" 
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500" 
                                            required
                                        >
                                            @foreach ($species as $specie)
                                                <option value="{{ $specie->id }}" {{ (old('species_id', $breed->species_id) == $specie->id) ? 'selected' : '' }}>
                                                    {{ $specie->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-atlas text-gray-400"></i>
                                        </div>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('species_id')
                                        <p class="mt-1 text-sm text-red-600">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </p>
                                    @else
                                        <p class="mt-1 text-xs text-gray-500">
                                            <i class="fas fa-info-circle mr-1"></i> Select the species this breed belongs to
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4 pt-4">
                            <a href="javascript:history.back()" class="inline-flex items-center px-5 py-3 shadow-sm rounded-lg text-white bg-red-500 hover:bg-red-700 focus:outline-none transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i> Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-3 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 focus:outline-none transition duration-200">
                                <i class="fas fa-save mr-2"></i> Update Breed
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</x-app-layout>