<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ isset($package) ? 'Edit Paket' : 'Tambah Paket' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <form method="POST" 
                    action="{{ isset($package) ? route('admin.packages.update', $package) : route('admin.packages.store') }}">
                    @csrf
                    @if(isset($package)) @method('PUT') @endif

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Nama Paket</label>
                        <input type="text" name="name" value="{{ old('name', $package->name ?? '') }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium mb-2">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ old('price', $package->price ?? '') }}"
                                class="w-full border rounded px-3 py-2" min="0" step="100000" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-2">Durasi (jam)</label>
                            <input type="number" name="duration_hours"
                                value="{{ old('duration_hours', $package->duration_hours ?? 8) }}"
                                class="w-full border rounded px-3 py-2" min="1" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full border rounded px-3 py-2" required>
                            {{ old('description', $package->description ?? '') }}
                        </textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium mb-2">Fasilitas</label>
                        <div id="features-container">
                            @php
                                $features = old('features', isset($package) && $package->features ? $package->features : ['']);
                            @endphp
                            @foreach($features as $index => $feature)
                                <div class="flex gap-2 mb-2">
                                    <input type="text" name="features[]" value="{{ $feature }}"
                                        class="flex-1 border rounded px-3 py-2" placeholder="Contoh: 1 Fotografer">
                                    @if($index > 0)
                                        <button type="button" onclick="this.parentElement.remove()"
                                            class="btn-danger">×</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addFeature()" class="btn-secondary mt-2">+ Tambah
                            Fasilitas</button>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($package) ? $package->is_active : true) ? 'checked' : '' }} class="mr-2">
                            <span>Aktifkan paket</span>
                        </label>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('admin.packages.index') }}" class="btn-secondary">← Kembali</a>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addFeature() {
            const container = document.getElementById('features-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `
            <input type="text" name="features[]" class="flex-1 border rounded px-3 py-2" placeholder="Fasilitas">
            <button type="button" onclick="this.parentElement.remove()" class="btn-danger">×</button>
        `;
            container.appendChild(div);
        }
    </script>
</x-app-layout>