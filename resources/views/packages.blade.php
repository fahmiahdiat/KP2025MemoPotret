<x-guest-layout>
    {{-- 1. FONT & STYLE SETUP --}}
    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        .font-luxury { font-family: 'Cormorant Garamond', serif; }
        .font-modern { font-family: 'Plus Jakarta Sans', sans-serif; }
        .shadow-soft { box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05); }
    </style>
    @endpush

    {{-- 2. HEADER SECTION --}}
    <div class="relative bg-[#FDFCF8] pt-24 pb-12 overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/3 w-[600px] h-[600px] bg-orange-50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute top-0 left-0 -translate-y-1/2 -translate-x-1/3 w-[500px] h-[500px] bg-rose-50 rounded-full blur-3xl opacity-50"></div>

        <div class="relative mx-auto max-w-7xl px-6 lg:px-8 text-center z-10">
            <h2 class="font-modern text-xs font-bold tracking-[0.2em] text-rose-500 uppercase mb-4">Investment</h2>
            <h1 class="font-luxury text-5xl md:text-7xl text-stone-900 mb-6">Paket Eksklusif</h1>
            <p class="font-modern text-stone-600 text-lg font-light max-w-2xl mx-auto leading-relaxed">
                Temukan paket yang dirancang khusus untuk mengabadikan setiap detik berharga dalam perjalanan cinta Anda. Transparan, tanpa biaya tersembunyi.
            </p>
        </div>
    </div>

    {{-- 3. PACKAGES GRID --}}
    <div class="bg-[#FDFCF8] pb-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($packages as $package)
                @php
                    // 1️⃣ Default fallback (Unsplash)
                    $bgImage = 'https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=800&auto=format&fit=crop';

                    // 2️⃣ Jika package punya thumbnail → PAKAI ITU
                    if ($package->thumbnail) {
                        $bgImage = asset('storage/' . $package->thumbnail);
                    } else {
                        // 3️⃣ Jika tidak ada thumbnail → fallback logic lama
                        $pName = strtolower($package->name);

                        if (str_contains($pName, 'gold') || str_contains($pName, 'luxury')) {
                            $bgImage = 'https://images.unsplash.com/photo-1606800052052-a08af7148866?q=80&w=800&auto=format&fit=crop'; 
                        } elseif (str_contains($pName, 'silver') || str_contains($pName, 'prewed')) {
                            $bgImage = 'https://images.unsplash.com/photo-1522673607200-1645062cd958?q=80&w=800&auto=format&fit=crop'; 
                        } elseif (str_contains($pName, 'bronze')) {
                            $bgImage = 'https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?q=80&w=800&auto=format&fit=crop'; 
                        }
                    }

                    // Status Logic
                    $isActive = $package->is_active;
                    $cardOpacity = $isActive ? '' : 'grayscale opacity-80';
                    $pointerEvents = $isActive ? '' : 'pointer-events-none cursor-not-allowed';
                @endphp

                    <a href="{{ $isActive ? route('package.show', $package) : '#' }}" 
                       class="group relative flex flex-col h-[500px] w-full rounded-[2.5rem] overflow-hidden shadow-soft hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 {{ $cardOpacity }} {{ $pointerEvents }}">
                        
                        <img src="{{ $bgImage }}" 
                             alt="{{ $package->name }}" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-900 via-stone-900/40 to-transparent opacity-90 group-hover:opacity-80 transition-opacity"></div>

                        <div class="relative h-full p-8 flex flex-col justify-between z-10">
                            
                            <div class="flex justify-between items-start">
                                @if(!$isActive)
                                    <span class="px-4 py-2 bg-red-500/90 backdrop-blur-md rounded-full text-xs font-modern font-bold tracking-wider uppercase text-white shadow-sm">
                                        Full Booked
                                    </span>
                                @else
                                    <span class="px-4 py-2 bg-white/20 backdrop-blur-md border border-white/30 rounded-full text-xs font-modern font-bold tracking-wider uppercase text-white shadow-sm">
                                        {{ $package->name }}
                                    </span>
                                @endif
                            </div>

                            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <h3 class="font-luxury text-3xl text-white mb-2 group-hover:text-rose-200 transition-colors">
                                    {{ $package->name }}
                                </h3>

                                <p class="font-modern text-stone-300 text-sm line-clamp-2 mb-4 font-light">
                                    {{ $package->description }}
                                </p>

                                <div class="flex items-baseline gap-x-2 mb-4">
                                    <span class="font-modern text-2xl font-bold text-white">
                                        Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-4 text-stone-300 text-xs font-modern uppercase tracking-wider mb-6 border-t border-white/20 pt-4">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $package->duration_hours }} Jam
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        High Res
                                    </div>
                                </div>

                                <div class="w-full">
                                    @if($isActive)
                                        <div class="w-full py-3 bg-white text-stone-900 rounded-xl font-modern font-bold text-center text-sm uppercase tracking-widest hover:bg-rose-50 transition-colors">
                                            @auth
                                                @if(auth()->user()->isClient())
                                                    Lihat Paket
                                                @else
                                                    Lihat Detail
                                                @endif
                                            @else
                                                Lihat Detail
                                            @endauth
                                        </div>
                                    @else
                                        <div class="w-full py-3 bg-stone-700 text-stone-400 rounded-xl font-modern font-bold text-center text-sm uppercase tracking-widest cursor-not-allowed">
                                            Tidak Tersedia
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="relative bg-stone-900 py-24 px-6 border-t border-white/10">
        <div class="mx-auto max-w-4xl text-center">
            <h2 class="font-luxury text-4xl text-white mb-6">Belum Menemukan yang Cocok?</h2>
            <p class="font-modern text-stone-400 text-lg font-light mb-10">
                Kami menyediakan layanan <i>custom package</i> sesuai dengan kebutuhan dan budget acara Anda.
            </p>
            <a href="https://wa.me/628972943198" target="_blank"
               class="inline-flex items-center gap-2 px-8 py-4 bg-rose-600 rounded-full text-white font-modern font-bold hover:bg-rose-700 transition-all shadow-lg hover:-translate-y-1">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                Hubungi Kami via WhatsApp
            </a>
        </div>
    </div>
</x-guest-layout>