<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Paket Layanan</h2>
            <a href="{{ route('admin.packages.create') }}" class="btn-primary">+ Tambah</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($packages as $package)
                <div class="card">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-bold text-lg">{{ $package->name }}</h3>
                        @if($package->is_active)
                            <span class="badge-success">Aktif</span>
                        @else
                            <span class="badge-warning">Nonaktif</span>
                        @endif
                    </div>
                    
                    <div class="text-2xl font-bold text-indigo-600 mb-2">
                        Rp {{ number_format($package->price, 0, ',', '.') }}
                    </div>
                    
                    <p class="text-gray-600 mb-4">{{ $package->description }}</p>
                    
                    <div class="text-sm text-gray-500 mb-4">
                        Durasi: {{ $package->duration_hours }} jam
                    </div>

                    @if($package->features)
                        @php
                            // Decode JSON string ke array
                            $features = json_decode($package->features, true);
                        @endphp
                        
                        @if(is_array($features) && count($features) > 0)
                        <ul class="space-y-1 mb-6">
                            @foreach(array_slice($features, 0, 3) as $feature)
                            <li class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    @endif

                    <div class="flex space-x-2">
                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn-secondary flex-1">Edit</a>
                        <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus paket?')" class="btn-danger w-full">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            @if($packages->isEmpty())
            <div class="card text-center py-12">
                <p class="text-gray-500 mb-4">Belum ada paket</p>
                <a href="{{ route('admin.packages.create') }}" class="btn-primary">+ Tambah Paket</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>