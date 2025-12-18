<x-guest-layout>
    {{-- 1. FONT SETUP & STYLE SYSTEM --}}
    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap"
            rel="stylesheet">

        <style>
            /* Typography System */
            .font-luxury { font-family: 'Cormorant Garamond', serif; }
            .font-modern { font-family: 'Plus Jakarta Sans', sans-serif; }
            html { scroll-behavior: smooth; }
            .shadow-dreamy { box-shadow: 0 25px 50px -12px rgba(168, 162, 158, 0.15); }

            /* ===== CUSTOM CALENDAR STYLES ===== */
            #mini-calendar { font-family: 'Plus Jakarta Sans', sans-serif; }

            .calendar-day {
                width: 100%;
                aspect-ratio: 1; /* Kotak Sempurna */
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px; /* Lebih rounded di mobile */
                cursor: pointer;
                transition: all 0.2s ease;
                font-size: 13px; /* Font pas untuk mobile */
                font-weight: 500;
                color: #57534e;
                border: 1px solid transparent;
                background: white;
            }

            @media (min-width: 768px) {
                .calendar-day {
                    border-radius: 12px;
                    font-size: 14px;
                }
            }

            .calendar-day:hover:not(.other-month):not(.booked):not(.disabled) {
                background: #f5f5f4;
                border-color: #e7e5e4;
                transform: scale(1.05);
            }

            .calendar-day.selected {
                background: #1c1917;
                color: white;
                font-weight: 600;
                box-shadow: 0 4px 12px rgba(28, 25, 23, 0.2);
                border-color: #1c1917;
            }

            .calendar-day.today {
                border: 1px solid #d6d3d1;
                color: #1c1917;
                font-weight: 700;
            }

            .calendar-day.other-month {
                color: #e7e5e4;
                background: transparent;
                cursor: default;
            }

            .calendar-day.disabled {
                color: #e7e5e4;
                cursor: not-allowed;
            }

            .calendar-day.booked {
                background: #ffe4e6 !important;
                color: #e11d48 !important;
                text-decoration: line-through;
                cursor: not-allowed !important;
                opacity: 0.6;
                pointer-events: none !important;
            }

            /* ===== TIME PICKER STYLES ===== */
            .time-slot-btn {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 8px;
                border: 1px solid #e7e5e4;
                border-radius: 8px;
                background: white;
                cursor: pointer;
                transition: all 0.2s ease;
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-size: 12px;
            }

            @media (min-width: 768px) {
                .time-slot-btn {
                    padding: 10px;
                    border-radius: 12px;
                    font-size: 14px;
                }
            }

            .time-slot-btn:hover { border-color: #a8a29e; background: #fafaf9; }
            .time-slot-btn.selected { background: #1c1917; border-color: #1c1917; color: white; }

            /* Hide Scrollbar */
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    @endpush

    {{-- BACKGROUND BLOB --}}
    <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-orange-50/60 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-rose-50/60 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="relative pt-24 pb-12"> <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Breadcrumb --}}
            <div class="mb-6">
                <a href="{{ route('home') }}#Paket"
                    class="inline-flex items-center text-stone-500 hover:text-stone-900 transition-colors font-modern text-sm font-medium group">
                    <div class="w-8 h-8 rounded-full bg-white border border-stone-200 flex items-center justify-center mr-2 group-hover:border-stone-400 transition-colors shadow-sm">
                        <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    Kembali
                </a>
            </div>

            {{-- Grid Layout: Mobile (1 Col) -> Desktop (3 Col) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-start">
                
                {{-- LEFT COLUMN: INFO --}}
                <div class="lg:col-span-2 space-y-8">
                    @php
                        $pName = strtolower($package->name);
                        $bgImage = 'https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=800&auto=format&fit=crop';

                        if ($package->thumbnail) {
                            $bgImage = asset('storage/' . $package->thumbnail);
                        } else {
                            if (str_contains($pName, 'gold') || str_contains($pName, 'luxury')) {
                                $bgImage = 'https://images.unsplash.com/photo-1606800052052-a08af7148866?q=80&w=800&auto=format&fit=crop';
                            } elseif (str_contains($pName, 'silver') || str_contains($pName, 'prewed')) {
                                $bgImage = 'https://images.unsplash.com/photo-1522673607200-1645062cd958?q=80&w=800&auto=format&fit=crop';
                            } elseif (str_contains($pName, 'bronze')) {
                                $bgImage = 'https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?q=80&w=800&auto=format&fit=crop';
                            }
                        }
                    @endphp

                    {{-- Hero Image Card --}}
                    <div class="relative h-[350px] md:h-[500px] w-full rounded-[2rem] md:rounded-[3rem] overflow-hidden shadow-dreamy group">
                        <img src="{{ $bgImage }}" alt="{{ $package->name }}"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-105">

                        <div class="absolute inset-0 bg-gradient-to-t from-stone-900/90 via-stone-900/30 to-transparent opacity-90"></div>

                        <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10 text-white">
                            <span class="inline-block px-3 py-1.5 md:px-4 md:py-2 rounded-full text-[10px] md:text-xs font-bold font-modern tracking-[0.2em] uppercase bg-white/10 backdrop-blur-md border border-white/20 mb-3 md:mb-6">
                                {{ $package->name }}
                            </span>
                            <h1 class="font-luxury text-4xl md:text-7xl mb-2 md:mb-4 leading-none">{{ $package->name }}</h1>
                            <p class="font-modern text-sm md:text-lg font-light text-stone-200 max-w-xl leading-relaxed line-clamp-3 md:line-clamp-none">
                                {{ $package->description }}
                            </p>
                        </div>
                    </div>

                    {{-- Detail Card --}}
                    <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-10 shadow-sm border border-stone-100">
                        <div class="flex items-center justify-between mb-6 md:mb-8">
                            <h3 class="font-luxury text-2xl md:text-4xl text-stone-900">Detail Layanan</h3>
                            <div class="hidden md:block h-px flex-1 bg-stone-100 ml-8"></div>
                        </div>

                        {{-- Stats Grid (2 kolom di HP) --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-8 md:mb-10">
                            <div class="text-center p-4 bg-[#FAFAF9] rounded-2xl border border-stone-50">
                                <div class="font-luxury text-2xl md:text-4xl text-stone-800 mb-1">{{ $package->duration_hours }}</div>
                                <div class="font-modern text-[10px] font-bold text-stone-400 uppercase tracking-widest">Jam Liputan</div>
                            </div>
                            <div class="text-center p-4 bg-[#FAFAF9] rounded-2xl border border-stone-50">
                                <div class="font-luxury text-2xl md:text-4xl text-stone-800 mb-1">
                                    @if(str_contains($pName, 'gold')) 2 @else 1 @endif
                                </div>
                                <div class="font-modern text-[10px] font-bold text-stone-400 uppercase tracking-widest">Fotografer</div>
                            </div>
                            <div class="text-center p-4 bg-[#FAFAF9] rounded-2xl border border-stone-50">
                                <div class="font-luxury text-2xl md:text-4xl text-stone-800 mb-1">7</div>
                                <div class="font-modern text-[10px] font-bold text-stone-400 uppercase tracking-widest">Hari Kerja</div>
                            </div>
                            <div class="text-center p-4 bg-[#FAFAF9] rounded-2xl border border-stone-50">
                                <div class="font-luxury text-2xl md:text-4xl text-stone-800 mb-1">HQ</div>
                                <div class="font-modern text-[10px] font-bold text-stone-400 uppercase tracking-widest">High Res</div>
                            </div>
                        </div>

                        {{-- Features List --}}
                        @if(!empty($package->features) && is_array($package->features) && count($package->features) > 0)
                            <h4 class="font-modern text-xs md:text-sm font-bold text-stone-400 uppercase tracking-widest mb-4">Fasilitas Termasuk</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-4">
                                @foreach($package->features as $feature)
                                    <div class="flex items-start gap-3">
                                        <div class="w-5 h-5 rounded-full bg-rose-50 flex items-center justify-center flex-shrink-0 text-rose-500 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <span class="font-modern text-sm text-stone-600 leading-relaxed">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Other Packages --}}
                    @if($otherPackages->count() > 0)
                    <div x-data="{
                            scrollLeft() { this.$refs.track.scrollBy({ left: -300, behavior: 'smooth' }) },
                            scrollRight() { this.$refs.track.scrollBy({ left: 300, behavior: 'smooth' }) }
                        }"
                        class="pt-8 border-t border-stone-200 relative">
                        
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-luxury text-2xl text-stone-900">Paket Lainnya</h3>
                            {{-- Hide arrows on mobile, keep touch scroll --}}
                            <div class="hidden md:flex gap-2">
                                <button @click="scrollLeft" class="w-9 h-9 rounded-full bg-white border border-stone-200 flex items-center justify-center hover:bg-stone-900 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button @click="scrollRight" class="w-9 h-9 rounded-full bg-white border border-stone-200 flex items-center justify-center hover:bg-stone-900 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        <div x-ref="track" class="flex gap-4 overflow-x-auto scroll-smooth pb-4 -mx-4 px-4 md:mx-0 md:px-0 scrollbar-hide">
                            @foreach($otherPackages as $other)
                                @php
                                    $otherBg = 'https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=600';
                                    if ($other->thumbnail) {
                                        $otherBg = asset('storage/' . $other->thumbnail);
                                    }
                                @endphp
                                <a href="{{ route('package.show', $other) }}" class="group min-w-[280px] md:min-w-[300px] flex items-center gap-4 bg-white p-3 rounded-2xl border border-stone-100 shadow-sm hover:shadow-lg transition-all">
                                    <div class="relative w-20 h-20 rounded-xl overflow-hidden flex-shrink-0">
                                        <img src="{{ $otherBg }}" class="absolute inset-0 w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-luxury text-lg text-stone-900 leading-tight group-hover:text-rose-600 truncate">{{ $other->name }}</h4>
                                        <p class="font-modern text-xs text-stone-500 font-medium mt-1">Rp {{ number_format($other->price, 0, ',', '.') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- RIGHT COLUMN: BOOKING FORM (Sticky) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-6">
                        <div class="bg-white rounded-[2rem] shadow-dreamy border border-stone-100 p-5 md:p-8 relative overflow-hidden">
                            <div class="relative z-10 mb-6 text-center">
                                <h3 class="font-luxury text-2xl md:text-3xl text-stone-900 mb-2">Amankan Tanggal</h3>
                                <div class="flex items-center justify-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    <p class="font-modern text-stone-500 text-[10px] md:text-xs font-bold uppercase tracking-widest">Live Availability</p>
                                </div>
                            </div>

                            @auth
                                @if(auth()->user()->isClient())
                                    <form action="{{ route('client.bookings.create-step2') }}" method="GET" id="bookingStep1Form">
                                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                                        {{-- CALENDAR INPUT --}}
                                        <div class="mb-6">
                                            <label class="block font-modern text-[10px] md:text-xs font-bold text-stone-400 uppercase tracking-widest mb-3 px-1">Tanggal Acara</label>
                                            
                                            <div class="bg-[#FAFAF9] rounded-2xl md:rounded-3xl p-3 md:p-5 border border-stone-200">
                                                <input type="hidden" id="event_date" name="event_date" required>
                                                <div id="mini-calendar" class="w-full mx-auto">
                                                    <div class="flex items-center justify-between mb-4 px-1">
                                                        <button type="button" id="prev-month" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white hover:shadow-sm transition-all">
                                                            <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                                        </button>
                                                        <h3 id="calendar-month-year" class="font-modern text-xs font-bold text-stone-800 uppercase tracking-wide"></h3>
                                                        <button type="button" id="next-month" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white hover:shadow-sm transition-all">
                                                            <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                        </button>
                                                    </div>
                                                    <div class="grid grid-cols-7 gap-1 mb-2">
                                                        @foreach(['M', 'S', 'S', 'R', 'K', 'J', 'S'] as $day)
                                                            <div class="text-center font-modern text-[10px] font-bold text-stone-300 py-1">{{ $day }}</div>
                                                        @endforeach
                                                    </div>
                                                    <div id="calendar-days" class="grid grid-cols-7 gap-1"></div>
                                                </div>
                                            </div>
                                            <div id="selected-date" class="mt-3 p-3 bg-stone-900 rounded-xl hidden flex items-center justify-between animate-fade-in-up">
                                                <span class="font-modern text-[10px] font-bold text-stone-400 uppercase tracking-widest pl-2">Terpilih</span>
                                                <span id="selected-date-text" class="font-luxury text-base text-white pr-2"></span>
                                            </div>
                                        </div>

                                        {{-- TIME INPUT --}}
                                        <div class="mb-6">
                                            <label class="block font-modern text-[10px] md:text-xs font-bold text-stone-400 uppercase tracking-widest mb-3 px-1">Waktu Mulai</label>
                                            <input type="hidden" id="event_time" name="event_time" required>
                                            
                                            <div id="time-display" class="w-full bg-[#FAFAF9] border border-stone-200 rounded-xl p-3 flex items-center justify-between cursor-pointer hover:border-stone-400 transition-all group">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-stone-500">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
                                                    <div>
                                                        <span id="time-placeholder" class="font-modern text-xs md:text-sm text-stone-500 font-medium">--:-- WIB</span>
                                                        <span id="selected-time-text" class="font-luxury text-lg text-stone-900 hidden"></span>
                                                    </div>
                                                </div>
                                                <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>

                                            <div id="time-picker" class="hidden mt-2 p-3 bg-white border border-stone-100 rounded-xl shadow-xl absolute z-20 w-full left-0">
                                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 max-h-48 overflow-y-auto" id="time-slots-container"></div>
                                            </div>
                                        </div>

                                        {{-- AVAILABILITY & PRICE --}}
                                        <div id="availability-box" class="mb-6 p-4 rounded-xl bg-stone-50 border border-stone-100 flex items-center justify-between">
                                            <div>
                                                <p class="font-modern text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1">Status</p>
                                                <p class="font-luxury text-base text-stone-900 leading-none" id="avail-status-text">Cek dulu</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-modern text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1">Sisa Slot</p>
                                                <p class="font-luxury text-xl text-stone-900 leading-none" id="slot-info">-</p>
                                            </div>
                                        </div>

                                        <div class="mb-6 py-4 border-y border-stone-100 border-dashed">
                                            <div class="flex justify-between items-end mb-1">
                                                <span class="font-modern text-xs text-stone-500">Investasi</span>
                                                <span class="font-luxury text-2xl text-stone-900">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center text-[10px] text-stone-400 font-modern font-medium">
                                                <span>Pembayaran Awal (DP 50%)</span>
                                                <span>Rp {{ number_format($package->price * 0.5, 0, ',', '.') }}</span>
                                            </div>
                                        </div>

                                        <button type="submit" id="submit-btn" class="w-full py-4 bg-stone-900 text-white rounded-xl font-modern font-bold text-xs uppercase tracking-widest hover:bg-stone-800 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2" disabled>
                                            <span>Lanjut Booking</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center py-10 bg-stone-50 rounded-2xl border border-stone-100">
                                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 text-stone-400 shadow-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </div>
                                        <h4 class="font-luxury text-xl text-stone-900 mb-1">Mode Admin</h4>
                                        <a href="{{ route('admin.dashboard') }}" class="inline-block mt-4 px-6 py-2 bg-stone-900 text-white rounded-full font-modern text-[10px] font-bold uppercase tracking-widest">Ke Dashboard</a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8 bg-stone-50 rounded-2xl border border-stone-100">
                                    <h4 class="font-luxury text-xl text-stone-900 mb-2">Login Booking</h4>
                                    <p class="font-modern text-xs text-stone-500 mb-6 px-4">Amankan tanggal spesial Anda.</p>
                                    <div class="space-y-3 px-4">
                                        <button data-auth-modal data-auth-tab="login" class="w-full py-3 bg-stone-900 text-white rounded-xl font-modern font-bold text-[10px] uppercase tracking-widest">Masuk Akun</button>
                                        <button data-auth-modal data-auth-tab="register" class="w-full py-3 bg-white border border-stone-200 text-stone-900 rounded-xl font-modern font-bold text-[10px] uppercase tracking-widest">Daftar Baru</button>
                                    </div>
                                </div>
                            @endauth
                        </div>
                        
                        <div class="mt-6 text-center">
                            <a href="https://wa.me/62812345678" target="_blank" class="inline-flex items-center text-stone-400 hover:text-stone-800 transition-colors font-modern text-[10px] font-bold uppercase tracking-widest group">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>Bantuan via WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT TETAP SAMA (LOGIC TIDAK BERUBAH) --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bookedDates = @json($bookedDates ?? []);
            
            class CustomCalendar {
                constructor(containerId, bookedDates) {
                    this.container = document.getElementById(containerId);
                    this.currentDate = new Date();
                    this.selectedDate = null;
                    this.bookedDates = bookedDates;
                    this.init();
                }

                init() { this.render(); this.bindEvents(); }

                getMonthYearString() {
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    return `${months[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
                }
                
                getDaysInMonth(year, month) { return new Date(year, month + 1, 0).getDate(); }
                getFirstDayOfMonth(year, month) { let day = new Date(year, month, 1).getDay(); return day === 0 ? 7 : day; }
                formatDate(date) { 
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }
                isToday(date) {
                    const today = new Date();
                    return date.getDate() === today.getDate() && date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear();
                }
                isBooked(date) { return this.bookedDates.includes(this.formatDate(date)); }
                isPastDate(date) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    return date < today;
                }

                render() {
                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    document.getElementById('calendar-month-year').textContent = this.getMonthYearString();
                    
                    const daysInMonth = this.getDaysInMonth(year, month);
                    const firstDay = this.getFirstDayOfMonth(year, month);
                    const calendarDays = document.getElementById('calendar-days');
                    calendarDays.innerHTML = '';

                    for (let i = 1; i < firstDay; i++) {
                        const empty = document.createElement('div');
                        calendarDays.appendChild(empty);
                    }

                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(year, month, day);
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = day;
                        btn.className = 'calendar-day';
                        
                        const dateStr = this.formatDate(date);
                        
                        if (this.isPastDate(date)) {
                            btn.classList.add('disabled');
                            btn.disabled = true;
                        } else if (this.isBooked(date)) {
                            btn.classList.add('booked');
                            btn.disabled = true;
                        } else {
                            if (this.selectedDate && this.formatDate(this.selectedDate) === dateStr) {
                                btn.classList.add('selected');
                            } else if (this.isToday(date)) {
                                btn.classList.add('today');
                            }
                            btn.addEventListener('click', () => this.selectDate(date));
                        }
                        calendarDays.appendChild(btn);
                    }
                }

                selectDate(date) {
                    this.selectedDate = date;
                    this.render();
                    const dateStr = this.formatDate(date);
                    document.getElementById('event_date').value = dateStr;
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    document.getElementById('selected-date-text').textContent = date.toLocaleDateString('id-ID', options);
                    document.getElementById('selected-date').classList.remove('hidden');
                    checkSlotAvailability(dateStr);
                    checkFormCompletion();
                }
                
                prevMonth() { this.currentDate.setMonth(this.currentDate.getMonth() - 1); this.render(); }
                nextMonth() { this.currentDate.setMonth(this.currentDate.getMonth() + 1); this.render(); }
                
                bindEvents() {
                    document.getElementById('prev-month').addEventListener('click', () => this.prevMonth());
                    document.getElementById('next-month').addEventListener('click', () => this.nextMonth());
                }
            }

            const calendar = new CustomCalendar('mini-calendar', bookedDates);

            function checkSlotAvailability(date) {
                const slotInfo = document.getElementById('slot-info');
                const statusText = document.getElementById('avail-status-text');
                slotInfo.textContent = '...';
                
                fetch(`/api/date-slots/${date}`)
                    .then(res => res.json())
                    .then(data => {
                        slotInfo.textContent = data.available_slots;
                        if(data.is_full) {
                            statusText.innerHTML = '<span class="text-rose-500 font-bold">Penuh</span>';
                            document.getElementById('availability-box').classList.add('bg-rose-50', 'border-rose-100');
                        } else {
                            statusText.innerHTML = '<span class="text-green-600 font-bold">Tersedia</span>';
                            document.getElementById('availability-box').classList.remove('bg-rose-50', 'border-rose-100');
                            document.getElementById('availability-box').classList.add('bg-stone-50', 'border-stone-100');
                        }
                    })
                    .catch(() => { slotInfo.textContent = '5'; });
            }

            const timeDisplay = document.getElementById('time-display');
            const timePicker = document.getElementById('time-picker');
            const timeContainer = document.getElementById('time-slots-container');
            
            for(let i=6; i<=18; i++) {
                const hour = i.toString().padStart(2, '0');
                const times = [`${hour}:00`, `${hour}:30`];
                if(i==18) times.pop();
                
                times.forEach(t => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = t;
                    btn.className = 'time-slot-btn';
                    btn.onclick = () => selectTime(t, btn);
                    timeContainer.appendChild(btn);
                });
            }

            function selectTime(time, btnElement) {
                document.getElementById('event_time').value = time;
                document.getElementById('selected-time-text').textContent = time + ' WIB';
                document.getElementById('selected-time-text').classList.remove('hidden');
                document.getElementById('time-placeholder').classList.add('hidden');
                
                document.querySelectorAll('.time-slot-btn').forEach(b => { b.classList.remove('selected'); });
                btnElement.classList.add('selected');
                
                timePicker.classList.add('hidden');
                checkFormCompletion();
            }

            timeDisplay?.addEventListener('click', () => { timePicker.classList.toggle('hidden'); });

            document.addEventListener('click', function (event) {
                if (!timeDisplay.contains(event.target) && !timePicker.contains(event.target)) {
                    timePicker.classList.add('hidden');
                }
            });

            function checkFormCompletion() {
                const date = document.getElementById('event_date').value;
                const time = document.getElementById('event_time').value;
                const btn = document.getElementById('submit-btn');
                
                if(btn) {
                    btn.disabled = !(date && time);
                    if(date && time) {
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            }
        });
    </script>
    @endpush
</x-guest-layout>