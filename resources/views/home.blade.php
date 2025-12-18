<x-guest-layout>
    @push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        /* Typography System */
        .font-luxury { font-family: 'Cormorant Garamond', serif; }
        .font-modern { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Smooth Scrolling */
        html { scroll-behavior: smooth; }

        /* Custom Soft Shadow for Cards */
        .shadow-soft {
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        }
    </style>
    @endpush

    <div class="relative bg-[#FDFCF8] pt-24 pb-16 lg:pt-32 lg:pb-24 overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/3 w-[600px] h-[600px] bg-orange-50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[500px] h-[500px] bg-rose-50 rounded-full blur-3xl opacity-50"></div>

        <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-stone-100 shadow-sm mb-8 animate-fade-in-up">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="font-modern text-xs font-medium tracking-widest text-stone-500 uppercase">Open Slot 2025</span>
            </div>

            <h1 class="font-luxury text-6xl md:text-8xl font-medium text-stone-900 leading-[1.1] mb-6">
                Here Begins and <br>
                <span class="italic text-stone-500">Ends Happily.</span>
            </h1>

            <p class="font-modern text-stone-600 text-lg md:text-xl font-light max-w-2xl mx-auto mb-10 leading-relaxed">
                Kami percaya pernikahan bukan sekadar acara, melainkan warisan ingatan. Memo Potret hadir untuk memastikan setiap detiknya abadi dengan estetika yang jujur.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    @if(auth()->user()->isClient())
                        <a href="{{ route('packages') }}" class="group relative px-8 py-4 bg-stone-900 rounded-full text-white font-modern text-sm font-medium tracking-wide overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300">
                            <span class="relative z-10">Reservasi Tanggal</span>
                            <div class="absolute inset-0 bg-stone-800 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                        </a>
                    @endif
                @else
                    <button data-auth-modal data-auth-tab="register" class="group relative px-8 py-4 bg-stone-900 rounded-full text-white font-modern text-sm font-medium tracking-wide overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300">
                        <span class="relative z-10">Mulai Konsultasi</span>
                        <div class="absolute inset-0 bg-stone-800 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                    </button>
                @endauth
                
                <a href="#koleksi" class="px-8 py-4 rounded-full bg-white border border-stone-200 text-stone-600 font-modern text-sm font-medium tracking-wide hover:bg-stone-50 transition-colors">
                    Lihat Paket
                </a>
            </div>

            <div class="mt-16 sm:mt-24 relative">
                <div class="aspect-[16/9] sm:aspect-[21/9] rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <img src="https://plus.unsplash.com/premium_photo-1681841695231-d674aa32f65b?q=80&w=843&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                         alt="Cinematic Wedding" 
                         class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-[2s]">
                </div>
            </div>
        </div>
    </div>

    <div id="koleksi" class="py-24 bg-white relative">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="text-center mb-20">
            <h2 class="font-modern text-xs font-bold tracking-[0.2em] text-stone-400 uppercase mb-4">
                Investasi Kenangan
            </h2>
            <p class="font-luxury text-5xl md:text-6xl text-stone-900">
                Koleksi Eksklusif
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($packages as $package)
                @php
                    $bgImage = 'https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=800&auto=format&fit=crop';
                    if ($package->thumbnail) {
                        $bgImage = asset('storage/' . $package->thumbnail);
                    }
                @endphp

                <a href="{{ route('package.show', $package) }}"
                   class="group relative h-[460px] rounded-[2.5rem] overflow-hidden shadow-soft hover:shadow-2xl transition-all duration-500">

                    {{-- IMAGE --}}
                    <img src="{{ $bgImage }}"
                         alt="{{ $package->name }}"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                    {{-- OVERLAY --}}
                    <div class="absolute inset-0 bg-black/25 group-hover:bg-black/15 transition-colors"></div>

                    {{-- HEADER --}}
                    <div class="absolute top-0 left-0 right-0 p-6 bg-gradient-to-b from-black/60 to-transparent">
                        <h3 class="font-luxury text-2xl leading-snug text-white">
                            {{ $package->name }}
                        </h3>
                        <p class="font-modern text-sm text-stone-200 mt-1">
                            Mulai dari
                            <span class="font-semibold text-white">
                                Rp {{ number_format($package->price / 1000000, 1, ',', '.') }} Juta
                            </span>
                        </p>
                    </div>

                    {{-- FOOTER --}}
                    <div class="absolute bottom-4 left-4 right-4">
                        <div
                            class="flex items-center justify-between px-5 py-3 rounded-full
                                   bg-white/90 backdrop-blur-md shadow-md
                                   group-hover:bg-stone-900 group-hover:text-white transition-all">

                            <div class="flex items-center gap-2 text-sm font-modern">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $package->duration_hours }} Jam Coverage</span>
                            </div>

                            <span
                                class="w-9 h-9 rounded-full bg-stone-900 text-white flex items-center justify-center
                                       group-hover:bg-white group-hover:text-stone-900 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                </a>
            @endforeach
        </div>
    </div>
</div>


    <div class="py-24 bg-[#FDFCF8]">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-700">
                        <img src="https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?q=80&w=1200&auto=format&fit=crop" 
                             alt="Wedding Rings" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-10 -left-10 bg-white p-8 rounded-[2rem] shadow-xl max-w-xs hidden md:block animate-bounce-slow">
                        <p class="font-luxury text-2xl italic text-stone-900 mb-2">"Foto adalah mesin waktu."</p>
                        <p class="font-modern text-xs text-stone-400 uppercase tracking-widest">Memo Potret Philosophy</p>
                    </div>
                </div>

                <div>
                    <h2 class="font-luxury text-5xl md:text-6xl text-stone-900 mb-8 leading-tight">
                        Mengapa Memilih <br> <span class="italic text-stone-500">Memo Potret?</span>
                    </h2>
                    
                    <div class="space-y-10">
                        <div class="flex gap-5">
                            <div class="flex-none w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-900">
                                <i class="fas fa-camera-retro text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-modern text-lg font-bold text-stone-900">Gaya Natural & Timeless</h3>
                                <p class="font-modern text-stone-600 mt-2 font-light leading-relaxed">
                                    Kami menghindari filter berlebihan. Foto Anda akan tetap terlihat elegan dan relevan dalam 10, 20, hingga 50 tahun ke depan.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-5">
                            <div class="flex-none w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-900">
                                <i class="fas fa-heart text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-modern text-lg font-bold text-stone-900">Lebih dari Sekadar Vendor</h3>
                                <p class="font-modern text-stone-600 mt-2 font-light leading-relaxed">
                                    Kami menjadi sahabat Anda di hari H. Membantu mengarahkan pose dengan santai, menenangkan gugup, dan menangkap tawa jujur.
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex gap-5">
                            <div class="flex-none w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-900">
                                <i class="fas fa-gem text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-modern text-lg font-bold text-stone-900">Kualitas Premium</h3>
                                <p class="font-modern text-stone-600 mt-2 font-light leading-relaxed">
                                    Gear terbaik dan proses editing mendalam untuk memastikan setiap detail gaun, dekorasi, dan emosi terekam sempurna.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-24 bg-white px-6">
        <div class="mx-auto max-w-7xl">
            <div class="relative rounded-[3rem] overflow-hidden bg-stone-900 px-6 py-24 text-center shadow-2xl">
                <img src="https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=2000" 
                     class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay">
                
                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="font-luxury text-4xl md:text-6xl text-white mb-6">Siap Menulis Cerita?</h2>
                    <p class="font-modern text-stone-300 text-lg font-light mb-10">
                        Tanggal favorit cepat terisi. Mari berdiskusi tentang visi pernikahan impian Anda bersama kami.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="https://wa.me/62812345678" target="_blank"
                           class="px-8 py-4 bg-white rounded-full text-stone-900 font-modern font-bold hover:bg-stone-100 transition-colors w-full sm:w-auto">
                            Chat WhatsApp
                        </a>
                        @auth
                            @if(auth()->user()->isClient())
                                <a href="{{ route('packages') }}" class="px-8 py-4 bg-transparent border border-white/30 rounded-full text-white font-modern font-bold hover:bg-white/10 transition-colors w-full sm:w-auto">
                                    Lihat Paket
                                </a>
                            @endif
                        @else
                            <button data-auth-modal data-auth-tab="register" class="px-8 py-4 bg-transparent border border-white/30 rounded-full text-white font-modern font-bold hover:bg-white/10 transition-colors w-full sm:w-auto">
                                Buat Akun
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>