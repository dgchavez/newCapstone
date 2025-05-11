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
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Custom Background Style -->
    <style>
        body {
            background-image: url('{{ asset('assets/bg6.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Footer Collapse/Expand Styles */
        .collapsible-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 40;
        }

        /* Ensure main content doesn't get hidden behind footer */
        main {
            padding-bottom: 50px;
        }
        #animal-card {
            min-height: 200px; /* or whatever is appropriate */
            height: auto;
            overflow: visible;
        }
    </style>
</head>

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

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 bg-opacity-80 flex flex-col">
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
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Collapsible Footer with Policy Links -->
        <footer x-data="policyModal()" class="collapsible-footer">
            <div 
                 x-data="{ expanded: false }" 
                 @mouseenter="expanded = true" 
                 @mouseleave="expanded = false"
                 class="bg-gray-800 text-white shadow-lg transition-all duration-300"
                 :class="{ 'shadow-lg': expanded }">
                
                <!-- Footer Handle - Always visible -->
                <div class="footer-handle h-10 flex justify-center items-center cursor-pointer border-t border-gray-700">
                    <div class="text-gray-400 transition-transform duration-300"
                         :class="expanded ? 'rotate-180' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                
                <!-- Footer Content - Expands on hover -->
                <div class="overflow-hidden transition-all duration-300 ease-in-out"
                     :style="expanded ? 'max-height: 200px' : 'max-height: 0px'">
                    <div class="container mx-auto px-4 py-6">
                        <div class="flex flex-wrap justify-center space-x-4 mb-4">
                            <!-- Policies Links -->
                            @foreach(\App\Models\Policy::where('is_published', true)->get() as $policy)
                                <a href="#" 
                                   @click.prevent="openPolicyModal({{ json_encode($policy) }})" 
                                   class="text-gray-300 hover:text-white hover:underline">
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
                        <p class="text-center text-gray-400 text-sm">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
            
            <!-- Policy View Modal -->
            <div x-show="showPolicyModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" @click="showPolicyModal = false">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                        @click.stop>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="currentPolicy?.title"></h3>
                            <div class="prose max-w-none text-gray-800" x-html="currentPolicy?.content"></div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="showPolicyModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Alpine.js Components -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('policyModal', () => ({
                showPolicyModal: false,
                currentPolicy: null,
                
                openPolicyModal(policy) {
                    this.currentPolicy = policy;
                    this.showPolicyModal = true;
                }
            }));
        });
    </script>
    
    @livewireScripts
</body>
</html>