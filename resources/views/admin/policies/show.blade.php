<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-4">{{ $policy->title }}</h1>
                <div class="prose max-w-none">
                    {!! $policy->content !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>