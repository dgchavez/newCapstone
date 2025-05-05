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
            background-image: url('{{ asset('assets/bg6.jpg') }}'); /* Path to your background image */
            background-size: cover; /* Makes the image cover the entire background */
            background-position: center; /* Centers the image */
            background-repeat: no-repeat; /* Prevents tiling */
            background-attachment: fixed; /* Keeps the background fixed on scroll */
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
    </div>
</body>
</html>
