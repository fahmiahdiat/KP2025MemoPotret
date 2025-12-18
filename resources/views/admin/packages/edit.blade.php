<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Edit Paket: {{ $package->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.packages.update', $package) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Paket</label>
                            <input type="text" name="name" value="{{ old('name', $package->name) }}"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                required>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                                <input type="number" name="price" value="{{ old('price', $package->price) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    min="0" step="100000" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (jam)</label>
                                <input type="number" name="duration_hours"
                                    value="{{ old('duration_hours', $package->duration_hours) }}"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    min="1" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                required>{{ old('description', $package->description) }}</textarea>
                        </div>

                        <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Thumbnail Paket
                        </label>

                        @if(isset($package) && $package->thumbnail)
                            <div class="mb-3">
                                <img 
                                    src="{{ asset('storage/' . $package->thumbnail) }}"
                                    alt="Thumbnail Paket"
                                    class="w-48 h-32 object-cover rounded-lg border border-gray-300 shadow-sm"
                                >
                            </div>
                        @endif

                        <input 
                            type="file" 
                            name="thumbnail"
                            accept="image/*"
                            class="block w-full text-sm text-gray-600
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100 cursor-pointer"
                        >

                        <p class="mt-1 text-xs text-gray-500">
                            Format: JPG, PNG, WEBP • Maksimal 2MB
                        </p>
                    </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas</label>
                            <div id="features-container">
                                @php
    $features = old('features');

    if ($features === null) {
        $features = is_array($package->features) && count($package->features)
            ? $package->features
            : [''];
    }
@endphp

                                @foreach($features as $index => $feature)
                                
                                    <div class="flex gap-2 mb-2">
                                        <input type="text" name="features[]" value="{{ $feature }}"
                                            class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                            placeholder="Contoh: 1 Fotografer">
                                        @if($index > 0)
                                            <button type="button" onclick="this.parentElement.remove()"
                                                class="px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">×</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addFeature()" 
                                class="mt-3 px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-100 border border-transparent rounded-md hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                + Tambah Fasilitas
                            </button>
                        </div>

                        <div class="mb-8">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" 
                                    {{ old('is_active', $package->is_active) ? 'checked' : '' }} 
                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                <span class="text-sm text-gray-700">Aktifkan paket</span>
                            </label>
                        </div>

                        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.packages.index') }}" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ← Kembali
                            </a>
                            <div class="flex gap-3">
                                <a href="{{ route('admin.packages.index') }}" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Batal
                                </a>
                                <button type="submit" 
                                    class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Update Paket
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addFeature() {
            const container = document.getElementById('features-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `
                <input type="text" name="features[]" 
                    class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                    placeholder="Fasilitas">
                <button type="button" onclick="this.parentElement.remove()" 
                    class="px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">×</button>
            `;
            container.appendChild(div);
        }
    </script>
</x-app-layout>