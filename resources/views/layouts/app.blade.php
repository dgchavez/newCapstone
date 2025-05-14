<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/icon.ico') }}" type="image/x-icon">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Background Style -->
    <style>
        body {
            background-image: url('{{ asset('assets/bg6.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            padding-bottom: 80px; /* Space for fixed footer */
        }
        
        /* Ensure proper modal display */
        [x-cloak] { display: none !important; }
        
        /* Improve content readability */
        .prose {
            color: #374151; /* gray-700 */
            line-height: 1.6;
        }
        
        .prose ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        
        .prose li {
            margin-bottom: 0.5rem;
        }
    </style>

    <script>
        // Prevent extension interference
        if (typeof browser === 'undefined') {
            window.browser = { runtime: {} };
        }
        
        // Prevent duplicate CKEditor loading
        if (typeof ClassicEditor === 'undefined') {
            const ckScript = document.createElement('script');
            ckScript.src = 'https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js';
            document.head.appendChild(ckScript);
        }
    </script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 bg-opacity-80">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Fixed Footer with Policy Links -->
        <div x-data="{ policyData: $store.policyStore }">
            <footer class="fixed bottom-0 left-0 right-0 bg-gray-800 text-white shadow-lg">
                <div class="container mx-auto px-4 py-3">
                    <div class="flex flex-wrap justify-center space-x-4 mb-2">
                        @foreach(\App\Models\Policy::where('is_published', true)->get() as $policy)
                            <a href="#" 
                               @click.prevent="policyData.openPolicyModal(@js($policy))" 
                               class="text-gray-300 hover:text-white hover:underline text-sm">
                                @switch($policy->type)
                                    @case('terms')
                                        Terms & Conditions
                                        @break
                                    @case('privacy')
                                        Privacy Policy
                                        @break
                                    @default
                                        Cookies Policy
                                @endswitch
                            </a>
                        @endforeach
                    </div>
                    <p class="text-center text-gray-400 text-xs">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                </div>
            </footer>

            <!-- Policy Modal (outside footer but still in body) -->
            <div x-show="policyData.showPolicyModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500 opacity-75" @click="policyData.closePolicyModal()"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white px-6 py-4 border-b flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900" x-text="policyData.currentPolicy?.title"></h3>
                        <button @click="policyData.closePolicyModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <template x-if="policyData.currentPolicy">
                            <div class="prose max-w-none" x-html="policyData.currentPolicy.content"></div>
                        </template>
                    </div>
                    
                    <div class="sticky bottom-0 bg-white px-6 py-3 border-t flex justify-end">
                        <button @click="policyData.closePolicyModal()" type="button" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts at the end of body -->
    <script>
        // Handle Livewire/Alpine conflicts
        document.addEventListener('livewire:init', () => {
            Livewire.hook('element.initialized', (el) => {
                Alpine.initTree(el);
            });
        });

        // Use Alpine store pattern instead
        document.addEventListener('alpine:init', () => {
            Alpine.store('policyStore', {
                showPolicyModal: false,
                currentPolicy: null,
                openPolicyModal(policy) {
                    this.currentPolicy = policy;
                    this.showPolicyModal = true;
                    document.body.style.overflow = 'hidden';
                },
                closePolicyModal() {
                    this.showPolicyModal = false;
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
</body>
</html>