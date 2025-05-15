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
    </div>
</body>
</html>