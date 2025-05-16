
<x-app-layout>
    <div class="bg-gradient-to-b from-green-50 to-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-600 to-green-400 h-16"></div>
                <div class="px-6 py-5 -mt-1">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-plus-circle text-blue-500 mr-3"></i>Add New Species
                    </h1>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <!-- Breadcrumb Navigation -->
                    <div class="flex items-center text-sm text-gray-500 mb-6">
                        <a href="{{ route('newspecies.index') }}" class="hover:text-blue-600 flex items-center">
                            <i class="fas fa-home mr-2"></i> Species Management
                        </a>
                        <span class="mx-2">/</span>
                        <span class="font-medium text-gray-700">Add New Species</span>
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

                    <form action="{{ route('newspecies.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                        <i class="fas fa-tag text-gray-400 mr-1"></i> Species Name
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            name="name" 
                                            id="name" 
                                            placeholder="Enter species name"
                                            value="{{ old('name') }}"
                                            class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                                            required
                                        >
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-paw text-gray-400"></i>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i> Enter a unique species name (e.g., Canine, Feline, etc.)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4 pt-4">
                            <a href="javascript:history.back()" class="inline-flex items-center px-5 py-3 shadow-sm rounded-lg text-white bg-red-500 hover:bg-red-700 focus:outline-none transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i> Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none transition duration-200">
                                <i class="fas fa-save mr-2"></i> Save Species
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