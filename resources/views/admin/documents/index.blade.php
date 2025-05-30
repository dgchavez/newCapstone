<x-app-layout>
       <!-- Page Header -->
       <div class="mx-auto flex flex-col max-w-sm items-center gap-x-4 py-6">
        <img src="{{ asset('assets/logo2.png') }}" alt="logo" class="header-logo  w-16">
        <h1 class="text-3xl font-bold text-gray-900">
            Documents Management
        </h1>
        <p class="text-sm text-gray-500">
            Manage all your personal documents in the system
    </div
<div class="py-6">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        
        <div class="flex justify-between items-center mb-6">
            
            <h2 class="text-2xl font-semibold text-gray-900">My Documents</h2>
            <button 
                onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Upload Document
            </button>
        </div>

        <!-- Search and Filter -->
        <form action="{{ route('admin.documents.index') }}" method="GET" class="mb-6 flex gap-4">
            <div class="flex-1">
                <input 
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search documents..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
            <select 
                name="category"
                class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ ucfirst($category) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                Filter
            </button>
        </form>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Documents Grid -->
        @if($documents->count())

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($documents as $document)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $document->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $document->description }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.documents.download', $document) }}" 
                           class="text-green-600 hover:text-green-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                        <form id="deleteDocForm-{{ $document->id }}" 
                              action="{{ route('admin.documents.destroy', $document) }}" 
                              method="POST" 
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="text-red-600 hover:text-red-700" 
                                    onclick="confirmDocDelete('{{ $document->id }}', '{{ $document->title }}')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ ucfirst($document->category) }}
                    </span>
                    <span class="text-xs text-gray-500 ml-2">
                        {{ number_format($document->file_size / 1024, 2) }} KB
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $documents->links() }}
        </div>
        @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h1a4 4 0 010 8h-5zm0 0a5 5 0 01-5-5V7a5 5 0 0110 0v5" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No documents found</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by uploading your first document.</p>
    </div>
@endif

        <!-- Upload Modal -->
        <div id="uploadModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Document Title</label>
                                    <input type="text" name="title" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Category</label>
                                    <select name="category" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="general">General</option>
                                        <option value="medical">Medical</option>
                                        <option value="prescription">Prescription</option>
                                        <option value="lab_result">Lab Result</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">File</label>
                                    <input type="file" name="file" required
                                           class="mt-1 block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-md file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-green-50 file:text-green-700
                                                  hover:file:bg-green-100">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Upload
                            </button>
                            <button type="button"
                                    onclick="document.getElementById('uploadModal').classList.add('hidden')"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Document Confirmation Modal -->
<div id="deleteDocModal" class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full">
        <div class="text-center mb-5">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Delete Document</h3>
            <p id="deleteDocMessage" class="text-sm text-gray-600 mt-2"></p>
        </div>
        
        <div class="flex space-x-3 justify-center">
            <button id="confirmDocDeleteBtn" type="button" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                Delete
            </button>
            <button id="cancelDocDeleteBtn" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    // Function to confirm document deletion
    function confirmDocDelete(docId, docTitle) {
        const modal = document.getElementById('deleteDocModal');
        const message = document.getElementById('deleteDocMessage');
        
        // Set the message with stronger warning
        message.textContent = `Are you sure you want to delete "${docTitle}"? This action cannot be undone.`;
        
        // Store the form to submit when confirmed
        const form = document.getElementById(`deleteDocForm-${docId}`);
        
        // Set up the confirm button
        document.getElementById('confirmDocDeleteBtn').onclick = function() {
            form.submit();
        };
        
        // Set up the cancel button
        document.getElementById('cancelDocDeleteBtn').onclick = closeDeleteDocModal;
        
        // Show the modal
        modal.classList.remove('hidden');
        
        // Close when clicking outside the modal
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteDocModal();
            }
        }, { once: true });
    }
    
    // Function to close the delete document modal
    function closeDeleteDocModal() {
        document.getElementById('deleteDocModal').classList.add('hidden');
    }
    
    // Set up event handlers when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteDocModal');
        if (deleteModal) {
            deleteModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteDocModal();
                }
            });
        }
    });
</script>
</x-app-layout>