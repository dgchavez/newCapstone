<!-- resources/views/barangays/index.blade.php -->

<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Barangays</h1>
        <a href="{{ route('newcreate-barangay') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-3 inline-block">Add Barangay</a>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Barangay Name</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($barangays as $barangay)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $barangay->barangay_name }}</td>
                            <td class="px-4 py-2">
                             
                         
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
