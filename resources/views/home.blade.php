<x-guest-layout>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-blue-50 to-purple-50">
        <div class="px-6 pt-20 pb-16 sm:px-12 lg:px-8 lg:pt-32 lg:pb-20">
            <div class="mx-auto max-w-2xl text-center">
                <h1 class="text-4xl font-bold text-gray-900 sm:text-6xl">
                    Capture Your Precious Moments
                </h1>
                <p class="mt-6 text-lg text-gray-600">
                    Studio foto profesional untuk wedding & prewedding Anda
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        @if(auth()->user()->isClient())
                            <a href="{{ route('client.bookings.create') }}"
                                class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                                📅 Buat Booking
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}"
                            class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                            ✨ Daftar Sekarang
                        </a>
                    @endauth
                    <a href="#packages"
                        class="px-6 py-3 bg-white text-indigo-600 font-medium rounded-lg border border-indigo-200 hover:bg-indigo-50 transition">
                        👇 Lihat Paket
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Packages Section -->
    <div id="packages" class="py-16 bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Paket Layanan Kami</h2>
                <p class="mt-2 text-gray-600">Pilih paket terbaik untuk momen spesial Anda</p>
            </div>

            <div class="grid gap-6 sm:gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 max-w-5xl mx-auto">
                @foreach($packages as $package)
                            <a href="{{route('package.show', $package)}}#package-{{ $package->id }}"
                                class="group relative block overflow-hidden rounded-xl bg-white shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 aspect-[3/4]">
                                <!-- Full Background Image -->
                                <div class="absolute inset-0">
                                    <!-- Gradient Background for each package -->
                                    <div class="absolute inset-0 bg-gradient-to-b 
                                                        {{ $package->name == 'Paket Gold' ? 'from-amber-600 via-amber-500 to-yellow-400' :
                    ($package->name == 'Paket Silver' ? 'from-gray-600 via-gray-500 to-gray-300' :
                        'from-orange-600 via-orange-500 to-red-300') }}">
                                        <!-- Emoji/Icon as decorative element -->
                                        <div class="absolute inset-0 flex items-center justify-center opacity-20">
                                            @if($package->name == 'Paket Gold')
                                                <div class="text-9xl">👑</div>
                                            @elseif($package->name == 'Paket Silver')
                                                <div class="text-9xl">✨</div>
                                            @else
                                                <div class="text-9xl">📸</div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Gradient Overlay for text readability -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                                </div>

                                <!-- Content Container -->
                                <div class="relative h-full p-6 flex flex-col justify-end">
                                    <!-- Top Section: Badge -->
                                    <div class="mb-auto pt-4">
                                        <span class="inline-block px-4 py-1.5 text-xs font-bold tracking-wider uppercase rounded-full 
                                                            {{ $package->name == 'Paket Gold' ? 'bg-yellow-500/90 text-white backdrop-blur-sm' :
                    ($package->name == 'Paket Silver' ? 'bg-gray-700/90 text-white backdrop-blur-sm' :
                        'bg-orange-500/90 text-white backdrop-blur-sm') }}">
                                            {{ $package->name == 'Paket Gold' ? 'PREMIUM' :
                    ($package->name == 'Paket Silver' ? 'POPULER' : 'DASAR') }}
                                        </span>
                                    </div>

                                    <!-- Bottom Section: Package Info -->
                                    <div>
                                        <!-- Package Name -->
                                        <h3 class="text-2xl sm:text-2xl md:text-3xl font-bold text-white mb-3 leading-tight">
                                            {{ $package->name }}
                                        </h3>

                                        <!-- Price -->
                                        <div class="flex flex-col">
                                            <span class="text-3xl sm:text-3xl md:text-4xl font-bold text-white leading-none mb-1">
                                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-white/80 text-sm font-medium">
                                                {{ $package->duration_hours }} jam session
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300"></div>

                                <!-- Hover Indicator -->
                                <div
                                    class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <div
                                        class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-full text-xs font-semibold text-gray-800 flex items-center gap-2 shadow-md">
                                        <span>Detail</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                @endforeach
            </div>

            <!-- CTA -->
            <div class="mt-12 text-center">
                <div class="inline-flex flex-col sm:flex-row gap-4 items-center">
                    <p class="text-gray-600">Butuh paket khusus? Hubungi kami!</p>
                    <a href="https://wa.me/628972943198" target="_blank"
                        class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition inline-flex items-center gap-2">
                        <span>💬</span> Konsultasi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="py-16 bg-gray-50">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Kenapa Memilih Memo Potret?</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Profesional</h3>
                    <p class="text-gray-600">Tim fotografer berpengalaman</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Cepat</h3>
                    <p class="text-gray-600">Hasil maksimal 7 hari kerja</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">💎</span>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Kualitas</h3>
                    <p class="text-gray-600">Editing premium & hasil maksimal</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Final CTA -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Abadikan Momen Anda?</h2>
            <p class="text-indigo-100 mb-8">Booking sekarang dan dapatkan pengalaman fotografi terbaik</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    @if(auth()->user()->isClient())
                        <a href="{{ route('client.bookings.create') }}"
                            class="px-8 py-3 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition">
                            📅 Buat Booking Sekarang
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}"
                        class="px-8 py-3 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition">
                        ✨ Daftar & Booking
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white/10 transition">
                        🔐 Login Akun
                    </a>
                @endauth
            </div>
        </div>
    </div>
</x-guest-layout>

<style>
    .aspect-\[3\/4\] {
        aspect-ratio: 3/4;
    }
</style>