<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- SEARCH + FILTER --}}
            <form method="GET" class="mb-8">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama paket..."
                            class="w-full rounded-xl border-gray-300 pl-11 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m1.6-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <div class="w-full md:w-48">
                        <select name="status"
                            class="w-full rounded-xl border-gray-300 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                        Terapkan
                    </button>
                </div>
            </form>
            @if($packages->isEmpty())
                <div
                    class="flex flex-col items-center justify-center py-16 bg-white rounded-3xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Belum ada paket layanan</h3>
                    <p class="text-gray-500 mb-6 text-sm">Mulai dengan menambahkan paket pertama Anda.</p>
                    <a href="{{ route('admin.packages.create') }}"
                        class="px-5 py-2.5 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition">
                        + Buat Paket Sekarang
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    <a href="{{ route('admin.packages.create') }}"
                        class="group relative flex flex-col items-center justify-center h-full min-h-[500px] rounded-[1.5rem] border-2 border-dashed border-gray-300 hover:border-indigo-500 hover:bg-indigo-50/30 transition-all duration-300 cursor-pointer text-center p-8">
                        <div
                            class="w-16 h-16 rounded-full bg-gray-100 group-hover:bg-indigo-100 flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-8 h-8 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">Buat Paket
                            Baru</h3>
                        <p class="text-sm text-gray-500 mt-2 group-hover:text-indigo-500 transition-colors">Tambahkan
                            variasi layanan baru untuk klien Anda.</p>
                    </a>

                    @foreach($packages as $package)
                            <div
                                class="group bg-white rounded-[1.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 flex flex-col h-full relative overflow-hidden">

                                {{-- Thumbnail Section --}}
                                <div class="relative h-48 w-full bg-gray-100 overflow-hidden">
                                    @if($package->thumbnail)
                                        <img src="{{ asset('storage/' . $package->thumbnail) }}" alt="{{ $package->name }}"
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center relative overflow-hidden">
                                            <div class="absolute inset-0 opacity-10"
                                                style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 20px 20px;">
                                            </div>
                                            <svg class="w-12 h-12 text-indigo-200" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/50 to-transparent">
                                    </div>

                                    <div class="absolute top-4 right-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider backdrop-blur-md border shadow-sm
                                                {{ $package->is_active
                        ? 'bg-green-500/90 text-white border-green-400/50'
                        : 'bg-gray-500/90 text-white border-gray-400/50' }}">
                                            @if($package->is_active)
                                                <span class="w-1.5 h-1.5 rounded-full bg-white mr-1.5 animate-pulse"></span>
                                            @endif
                                            {{ $package->is_active ? 'Aktif' : 'Draft' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Card Header --}}
                                <div class="px-6 pt-5 pb-2">
                                    <h3 class="text-xl font-bold text-gray-900 leading-snug mb-3 group-hover:text-indigo-600 transition-colors line-clamp-1"
                                        title="{{ $package->name }}">
                                        {{ $package->name }}
                                    </h3>

                                    <div class="flex items-baseline gap-1 mb-4">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">IDR</span>
                                        <span class="text-3xl font-extrabold text-gray-900 tracking-tight">
                                            {{ number_format($package->price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 h-10 mb-3">
                                        {{ $package->description }}
                                    </p>

                                    <div
                                        class="flex items-center text-xs font-medium text-gray-500 bg-gray-50 rounded-lg py-2 px-3 w-fit">
                                        <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $package->duration_hours }} Jam Liputan
                                    </div>
                                </div>

                                <div class="h-px bg-gray-100 mx-6 my-2"></div>

                                {{-- Card Features --}}
                                <div class="px-6 py-2 flex-grow">
                                    @php
                                        $features = is_string($package->features) ? json_decode($package->features, true) : $package->features;
                                    @endphp

                                    @if(is_array($features) && count($features) > 0)
                                        <div class="space-y-3 mt-2">
                                            @foreach(array_slice($features, 0, 3) as $feature)
                                                <div class="flex items-start text-sm text-gray-600 group/item">
                                                    <div
                                                        class="flex-shrink-0 w-5 h-5 rounded-full bg-green-50 flex items-center justify-center mr-2.5 mt-0.5 group-hover/item:bg-green-100 transition-colors">
                                                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                                d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="line-clamp-1">{{ $feature }}</span>
                                                </div>
                                            @endforeach

                                            @if(count($features) > 3)
                                                <div class="flex items-center text-xs text-gray-400 pl-8 font-medium">
                                                    <span>+ {{ count($features) - 3 }} fasilitas lainnya</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div
                                            class="flex flex-col items-center justify-center h-20 text-gray-400 bg-gray-50/50 rounded-xl border border-dashed border-gray-200 mt-2">
                                            <span class="text-xs italic">Belum ada fasilitas</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Footer Actions --}}
                                <div class="px-6 py-5 mt-2 bg-gray-50/50 border-t border-gray-100 grid grid-cols-2 gap-3">
                                    <a href="{{ route('admin.packages.edit', $package) }}"
                                        class="flex items-center justify-center px-4 py-2.5 border border-gray-200 shadow-sm text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-200 transition-all">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus paket ini?')"
                                            class="w-full flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-700 transition-all">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>