<x-app-layout>
    <style>
        /* CKEditor Styles */
        .ck-editor__editable {
            min-height: 300px;
            border: 1px solid #d1d5db !important;
            border-radius: 0 0 0.375rem 0.375rem !important;
        }
        .ck.ck-toolbar {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem 0.375rem 0 0 !important;
            border-bottom: none !important;
        }
        .hidden-editor {
            display: none !important;
        }
        /* Important: Clean styling for placeholder */
        .editor-placeholder {
            min-height: 300px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: #f9fafb;
        }

        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

<script>
    // Alpine.js component for policy management with CKEditor proxy error fix
    const policyManager = {
        showCreateModal: false,
        showEditModal: false,
        showDeleteModal: false,
        showViewModal: false,
        currentPolicy: {
            id: null,
            title: '',
            type: '',
            content: '',
            is_published: false,
            version: 1
        },
        currentPolicyId: null,
        _editor: null, // Use non-reactive property to store editor
        editorInitialized: false,
        formErrors: [],
        isLoading: false,
        
        init() {
            // Initialize CKEditor if not already loaded
            if (typeof ClassicEditor === 'undefined') {
                this.loadCKEditorScript();
            } else {
                this.editorInitialized = true;
            }
            
            // Listen for escape key to close modals
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (this.showCreateModal) this.closeCreateModal();
                    if (this.showEditModal) this.closeEditModal();
                    if (this.showViewModal) this.closeViewModal();
                    if (this.showDeleteModal) this.showDeleteModal = false;
                }
            });
        },
        
        loadCKEditorScript() {
            const script = document.createElement('script');
            script.src = 'https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js';
            script.onload = () => {
                this.editorInitialized = true;
                console.log('CKEditor loaded successfully');
            };
            script.onerror = () => {
                console.error('Failed to load CKEditor');
                this.formErrors.push('Failed to load text editor. Please refresh the page.');
            };
            document.head.appendChild(script);
        },
        
        openCreateModal() {
            this.formErrors = [];
            this.showCreateModal = true;
            this.$nextTick(() => {
                setTimeout(() => {
                    this.initEditor('create-editor-placeholder', 'content');
                }, 150); // Slightly longer delay for more reliable initialization
            });
        },
        
        closeCreateModal() {
            this.destroyEditor().then(() => {
                this.showCreateModal = false;
                this.formErrors = [];
            });
        },
        
        openEditModal(policy) {
            this.formErrors = [];
            this.currentPolicy = JSON.parse(JSON.stringify(policy));
            this.showEditModal = true;
            this.$nextTick(() => {
                setTimeout(() => {
                    this.initEditor('edit-editor-placeholder', 'edit-content', this.currentPolicy.content);
                }, 150);
            });
        },
        
        closeEditModal() {
            this.destroyEditor().then(() => {
                this.showEditModal = false;
                this.formErrors = [];
            });
        },
        
        openViewModal(policy) {
            this.currentPolicy = policy;
            this.showViewModal = true;
        },
        
        closeViewModal() {
            this.showViewModal = false;
        },
        
        async initEditor(placeholderId, contentFieldId, initialContent = '') {
            try {
                // Destroy existing editor first
                await this.destroyEditor();
                
                const placeholder = document.getElementById(placeholderId);
                if (!placeholder) {
                    console.error(`Editor placeholder not found: ${placeholderId}`);
                    return;
                }
                
                // Create a unique ID for this editor instance - completely outside Alpine's reactivity
                const editorId = `ckeditor-${Date.now()}`;
                
                // Reset the placeholder and add a fresh container
                placeholder.innerHTML = `<div id="${editorId}" style="min-height:300px;"></div>`;
                
                // Get content as a simple string (avoiding Alpine proxies)
                let content = '';
                if (typeof initialContent === 'string') {
                    content = initialContent;
                } else if (initialContent) {
                    try {
                        content = JSON.parse(JSON.stringify(initialContent));
                    } catch (error) {
                        console.error('Failed to serialize initial content:', error);
                        content = '';
                    }
                }
                
                // Use global window to avoid Alpine's proxy system
                window._initCKEditor = (id, content, fieldId) => {
                    ClassicEditor.create(document.getElementById(id), {
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', 'link', '|',
                                'bulletedList', 'numberedList', '|',
                                'blockQuote', 'insertTable', '|',
                                'undo', 'redo'
                            ],
                            shouldNotGroupWhenFull: true
                        }
                    }).then(editor => {
                        // Store editor reference outside Alpine's reactivity
                        window._policyEditor = editor;
                        
                        // Store reference for our component
                        this._editor = editor;
                        
                        // Set initial content if provided
                        if (content) {
                            editor.setData(content);
                        }
                        
                        // Sync with hidden textarea without using Alpine's reactivity
                        const contentField = document.getElementById(fieldId);
                        if (contentField) {
                            contentField.value = editor.getData();
                            
                            // Update textarea on editor changes using direct DOM manipulation
                            editor.model.document.on('change:data', () => {
                                try {
                                    const edData = editor.getData();
                                    contentField.value = edData;
                                } catch (e) {
                                    console.error('Error in change:data handler:', e);
                                }
                            });
                        }
                    }).catch(error => {
                        console.error('CKEditor initialization failed:', error);
                        placeholder.innerHTML = '<div class="text-red-500">Editor failed to load. Please try again.</div>';
                    });
                };
                
                // Initialize the editor outside of Alpine's reactive context
                setTimeout(() => {
                    try {
                        window._initCKEditor(editorId, content, contentFieldId);
                    } catch (error) {
                        console.error('Error during editor initialization:', error);
                        this.formErrors.push('Failed to initialize text editor. Please try again.');
                    }
                }, 0);
                
            } catch (error) {
                console.error('Editor initialization error:', error);
                this.formErrors.push('An error occurred while initializing the editor');
            }
        },
        
        async destroyEditor() {
            try {
                // Clean up the global editor reference if it exists
                if (window._policyEditor) {
                    await window._policyEditor.destroy();
                    window._policyEditor = null;
                }
                
                // Also clean up our local reference
                this._editor = null;
                
                // Clean up the init function too
                if (window._initCKEditor) {
                    window._initCKEditor = null;
                }
            } catch (error) {
                console.error('Error destroying editor:', error);
            }
            return Promise.resolve();
        },
        
        validateForm(formId) {
            this.formErrors = [];
            const form = document.getElementById(formId);
            if (!form) {
                this.formErrors.push('Form not found');
                return false;
            }

            let isValid = true;

            // Validate required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    const fieldName = field.labels?.length ? field.labels[0].textContent : field.name;
                    this.formErrors.push(`${fieldName} is required`);
                    isValid = false;
                }
            });

            // Validate content
            const contentField = formId === 'create-form' ? 
                document.getElementById('content') : 
                document.getElementById('edit-content');
            
            if (!contentField || !contentField.value.trim()) {
                this.formErrors.push('Policy content is required');
                isValid = false;
            } else if (contentField.value.length > 10000) {
                this.formErrors.push('Policy content is too long (max 10,000 characters)');
                isValid = false;
            }

            return isValid;
        },
        
        async submitCreateForm() {
            try {
                // Get content directly from the global editor reference
                if (window._policyEditor) {
                    try {
                        const contentField = document.getElementById('content');
                        if (contentField) {
                            // Get data outside of Alpine's reactivity system
                            const editorData = window._policyEditor.getData();
                            contentField.value = editorData;
                        }
                    } catch (error) {
                        console.error('Error getting editor data:', error);
                        this.formErrors.push('Unable to get editor content. Please try again.');
                        return;
                    }
                }
                
                // Validate form
                if (!this.validateForm('create-form')) {
                    return false;
                }
                
                this.isLoading = true;
                
                // Submit form using regular fetch API
                const form = document.getElementById('create-form');
                const formData = new FormData(form);
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    this.handleFormErrors(data);
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                this.formErrors.push('An error occurred while submitting the form');
            } finally {
                this.isLoading = false;
            }
        },
        
        async submitEditForm() {
            try {
                // Get content directly from the global editor reference
                if (window._policyEditor) {
                    try {
                        const contentField = document.getElementById('edit-content');
                        if (contentField) {
                            // Get data outside of Alpine's reactivity system
                            const editorData = window._policyEditor.getData();
                            contentField.value = editorData;
                        }
                    } catch (error) {
                        console.error('Error getting editor data:', error);
                        this.formErrors.push('Unable to get editor content. Please try again.');
                        return;
                    }
                }
                
                // Validate form
                if (!this.validateForm('edit-form')) {
                    return false;
                }
                
                this.isLoading = true;
                
                // Submit form using regular fetch API
                const form = document.getElementById('edit-form');
                const formData = new FormData(form);
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'PUT',
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    this.handleFormErrors(data);
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                this.formErrors.push('An error occurred while submitting the form');
            } finally {
                this.isLoading = false;
            }
        },
        
        handleFormErrors(data) {
            if (data.errors) {
                this.formErrors = Object.values(data.errors).flat();
            } else if (data.message) {
                this.formErrors.push(data.message);
            } else {
                this.formErrors.push('An unknown error occurred');
            }
        }
    };
</script>

    <div x-data="policyManager" x-init="init()" class="container mx-auto px-4 py-6">

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Form Validation Errors -->
        <div x-show="formErrors.length > 0" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul>
                <template x-for="error in formErrors" :key="error">
                    <li x-text="error"></li>
                </template>
            </ul>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Legal Policies</h1>
            <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Create New Policy
            </button>
        </div>

        <!-- Policy Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($policies->isEmpty())
            <div class="p-6 text-center text-gray-500">
                No policies found. Create your first policy.
            </div>
            @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($policies as $policy)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap cursor-pointer" @click="openViewModal({{ json_encode($policy) }})">
                            <div class="text-sm font-medium text-gray-900">{{ $policy->title }}</div>
                            <div class="text-sm text-gray-500">Version {{ $policy->version }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @switch($policy->type)
                                @case('terms')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Terms & Conditions
                                    </span>
                                    @break
                                @case('privacy')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Privacy Policy
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Cookies Policy
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $policy->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $policy->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $policy->updated_at->format('M d, Y') }}
                            <div class="text-xs text-gray-400">{{ $policy->updated_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <form action="{{ route('policies.toggle-publish', $policy) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="{{ $policy->is_published ? 'text-yellow-600 hover:text-yellow-900' : 'text-indigo-600 hover:text-indigo-900' }}">
                                    {{ $policy->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                            <button @click="openEditModal({{ json_encode($policy) }})" class="text-blue-600 hover:text-blue-900">
                                Edit
                            </button>
                            <button @click="showDeleteModal = true; currentPolicyId = {{ $policy->id }}" class="text-red-600 hover:text-red-900">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        <!-- Create Policy Modal -->
        <div x-show="showCreateModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="closeCreateModal()">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                    @click.stop>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Create New Policy</h3>
                        
                        <!-- Display validation errors -->
                        <div x-show="formErrors.length > 0" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul>
                                <template x-for="error in formErrors" :key="error">
                                    <li x-text="error"></li>
                                </template>
                            </ul>
                        </div>
                        
                        <!-- Create Form -->
                        <form id="create-form" @submit.prevent="submitCreateForm()" action="{{ route('policies.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div class="mb-4">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                                    <input type="text" name="title" id="title" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ old('title') }}">
                                </div>

                                <div class="mb-4">
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Policy Type *</label>
                                    <select name="type" id="type" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Policy Type</option>
                                        <option value="terms" {{ old('type') == 'terms' ? 'selected' : '' }}>Terms & Conditions</option>
                                        <option value="privacy" {{ old('type') == 'privacy' ? 'selected' : '' }}>Privacy Policy</option>
                                        <option value="cookies" {{ old('type') == 'cookies' ? 'selected' : '' }}>Cookies Policy</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                                <textarea name="content" id="content" class="hidden-editor" required>{{ old('content') }}</textarea>
                                <div id="create-editor-placeholder" class="editor-placeholder"></div>
                            </div>

                            <div class="flex items-center mb-4">
                                <input type="checkbox" name="is_published" id="is_published" value="1"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    {{ old('is_published') ? 'checked' : '' }}>
                                <label for="is_published" class="ml-2 block text-sm text-gray-700">
                                    Publish immediately
                                </label>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" @click="closeCreateModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Save Policy
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Edit Policy Modal -->
        <div x-show="showEditModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="closeEditModal()">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                    @click.stop>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Policy</h3>
                        
                        <!-- Display validation errors -->
                        <div x-show="formErrors.length > 0" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul>
                                <template x-for="error in formErrors" :key="error">
                                    <li x-text="error"></li>
                                </template>
                            </ul>
                        </div>
                        
                        <!-- Fixed Edit Form -->
                        <form id="edit-form" @submit.prevent="submitEditForm()" x-bind:action="'{{ route('policies.index') }}/' + currentPolicy.id" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div class="mb-4">
                                    <label for="edit-title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                                    <input type="text" name="title" id="edit-title" x-model="currentPolicy.title" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="mb-4">
                                    <label for="edit-type" class="block text-sm font-medium text-gray-700 mb-1">Policy Type *</label>
                                    <select name="type" id="edit-type" x-model="currentPolicy.type" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="terms">Terms & Conditions</option>
                                        <option value="privacy">Privacy Policy</option>
                                        <option value="cookies">Cookies Policy</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                                <textarea name="content" id="edit-content" class="hidden-editor" required></textarea>
                                <div id="edit-editor-placeholder" class="editor-placeholder"></div>
                            </div>

                            <div class="flex items-center mb-4">
                                <input type="checkbox" name="is_published" id="edit-is_published" x-model="currentPolicy.is_published" value="1"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="edit-is_published" class="ml-2 block text-sm text-gray-700">
                                    Published
                                </label>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" @click="closeEditModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center" :class="{ 'opacity-75 cursor-not-allowed': isLoading }" :disabled="isLoading">
                                    <template x-if="isLoading">
                                        <span class="loading-spinner mr-2"></span>
                                    </template>
                                    <template x-if="!isLoading">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </template>
                                    Update Policy
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="showDeleteModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    @click.stop>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Policy</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Are you sure you want to delete this policy? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <!-- Fixed the form action URL -->
                        <form x-bind:action="'{{ route('policies.index') }}/' + currentPolicyId" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Delete
                            </button>
                        </form>
                        <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Policy Modal -->
        <div x-show="showViewModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" @click="closeViewModal()">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                    @click.stop>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="currentPolicy.title"></h3>
                        <div class="prose max-w-none" x-html="currentPolicy.content"></div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="closeViewModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>