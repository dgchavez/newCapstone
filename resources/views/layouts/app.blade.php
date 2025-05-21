<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <!-- Preload Critical Assets -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preload" as="style" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap">
    <link rel="preload" as="image" href="{{ asset('assets/bg6.jpg') }}">

    <!-- Styles -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/icon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Optimized Background Style -->
    <style>
        :root {
            --bg-overlay-opacity: 0.8;
        }
        
        body {
            background-image: url('{{ asset('assets/bg6.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        [x-cloak] { 
            display: none !important; 
        }
        
        .content-overlay {
            background-color: rgba(243, 244, 246, var(--bg-overlay-opacity));
            backdrop-filter: blur(4px);
        }
        
        .prose {
            color: #374151;
            line-height: 1.6;
        }
        
        .prose ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin: 1rem 0;
        }
        
        .prose li {
            margin-bottom: 0.5rem;
        }

        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }
        
        .min-h-screen {
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1 0 auto;
            padding-bottom: 5rem; /* Add space for footer */
        }
        
        .site-footer {
            flex-shrink: 0;
            width: 100%;
            background-color: rgba(31, 41, 55, 0.95); /* bg-gray-800/95 */
            backdrop-filter: blur(8px);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
window.downloadIdCard = function(type) {
    const card = document.getElementById('animal-card');
    if (!card) {
        alert('No card found to download!');
        return;
    }
    html2canvas(card, {
        backgroundColor: type === 'jpeg' ? '#FFFFFF' : null,
        useCORS: true,
        scale: 2 // or use: scale: window.devicePixelRatio
    }).then(canvas => {
        let link = document.createElement('a');
        link.download = 'animal-id-card.' + (type === 'jpeg' ? 'jpg' : 'png');
        link.href = canvas.toDataURL('image/' + type, 1.0);
        link.click();
    });
}
</script>
    <!-- Initialize Alpine.js Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('policyData', {
                showPolicyModal: false,
                currentPolicy: null,
                openPolicyModal(policy) {
                    this.currentPolicy = policy;
                    this.showPolicyModal = true;
                },
                closePolicyModal() {
                    this.showPolicyModal = false;
                    this.currentPolicy = null;
                }
            });
        });

        // Utility function for script loading
        const loadScript = (src) => {
            return new Promise((resolve, reject) => {
                if (window[src.split('/').pop().split('.')[0]]) {
                    resolve();
                    return;
                }
                const script = document.createElement('script');
                script.src = src;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        };

        // Load CKEditor if needed
        if (typeof ClassicEditor === 'undefined') {
            loadScript('https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js');
        }

        // Fallback for browser runtime
        if (typeof browser === 'undefined') {
            window.browser = { runtime: {} };
        }
    </script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 bg-opacity-80">
        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white/90 backdrop-blur-sm shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Main Content -->
        <main class="main-content">
            {{ $slot }}
        </main>

        <!-- Footer (now part of the main layout flow) -->
        <footer class="site-footer shadow-lg">
                <div class="container mx-auto px-4 py-3">
                <div class="flex flex-wrap justify-center gap-4 mb-2">
                        @foreach(\App\Models\Policy::where('is_published', true)->get() as $policy)
                        <button 
                            @click="$store.policyData.openPolicyModal(@js($policy))"
                            class="text-gray-300 hover:text-white hover:underline text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white rounded-md px-2 py-1">
                            {{ ucfirst($policy->type) }} Policy
                        </button>
                        @endforeach
                    </div>
                    <p class="text-center text-gray-400 text-xs">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                </div>
            </footer>

        <!-- Policy Modal (moved outside the footer but still in the main container) -->
        <div x-data x-show="$store.policyData.showPolicyModal" 
             x-transition
             @keydown.escape.window="$store.policyData.closePolicyModal()"
             role="dialog"
             aria-modal="true"
                 x-cloak
             class="fixed inset-0 z-50 overflow-y-auto">
            <div class="modal-overlay fixed inset-0" @click="$store.policyData.closePolicyModal()"></div>
                
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[80vh] flex flex-col">
                    <div class="sticky top-0 bg-white px-6 py-4 border-b rounded-t-xl flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900" x-text="$store.policyData.currentPolicy?.title"></h3>
                        <button @click="$store.policyData.closePolicyModal()" 
                                class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-1"
                                aria-label="Close modal">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto p-6">
                        <template x-if="$store.policyData.currentPolicy">
                            <div class="prose max-w-none" x-html="$store.policyData.currentPolicy.content"></div>
                        </template>
                    </div>
                    
                    <div class="sticky bottom-0 bg-white px-6 py-3 border-t rounded-b-xl flex justify-end">
                        <button @click="$store.policyData.closePolicyModal()" 
                                type="button" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>