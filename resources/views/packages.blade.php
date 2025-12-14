<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Paket Layanan</h1>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($packages as $package)
                <div class="card {{ !$package->is_active ? 'opacity-75' : '' }}">
                    @if(!$package->is_active)
                        <div class="badge-warning mb-4">Tidak Tersedia</div>
                    @endif
                    <h3 class="text-xl font-bold">{{ $package->name }}</h3>
                    <div class="mt-2 text-2xl font-bold text-indigo-600">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                    <p class="mt-2 text-gray-600">{{ $package->description }}</p>
                    <div class="mt-4">
                        <span class="text-sm text-gray-500">Durasi: {{ $package->duration_hours }} jam</span>
                    </div>
                    <div class="mt-6">
                        @auth
                            @if(auth()->user()->isClient())
                                <a href="{{ route('client.bookings.create') }}?package={{ $package->id }}" 
                                   class="btn-primary w-full {{ !$package->is_active ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    Pilih
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="btn-primary w-full">Daftar</a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-guest-layout>