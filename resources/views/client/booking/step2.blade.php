<x-app-layout>
    {{-- INSERT FONT & STYLE SECARA LOKAL AGAR KONSISTEN --}}
    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap"
            rel="stylesheet">

        <style>
            .font-luxury {
                font-family: 'Cormorant Garamond', serif;
            }

            .font-modern {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .shadow-soft {
                box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
            }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 max-w-4xl mx-auto">
            <div class="flex items-center gap-4">
                <a href="{{ route('package.show', $package->id) }}"
                    class="group flex items-center justify-center w-10 h-10 rounded-full bg-white border border-stone-200 text-stone-400 hover:border-stone-400 hover:text-stone-900 transition-all">
                    <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-stone-900 font-luxury italic">Finalisasi Booking</h2>
                    <p class="text-sm text-stone-500 font-modern">Langkah 2 dari 2: Detail & Pembayaran</p>
                </div>
            </div>

            {{-- Stepper Simple --}}
            <div class="flex items-center gap-2">
                <div class="h-2 w-8 rounded-full bg-stone-200"></div>
                <div class="h-2 w-8 rounded-full bg-stone-800"></div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#FDFCF8] min-h-screen font-modern">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div
                class="bg-white rounded-[1.5rem] border border-stone-100 shadow-soft p-6 md:p-8 mb-8 relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
                </div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex items-start gap-5">
                        <div
                            class="w-20 h-20 rounded-2xl overflow-hidden border border-stone-200 shadow-md flex-shrink-0">
                            <img src="{{ $package->thumbnail ? asset('storage/' . $package->thumbnail) : 'https://via.placeholder.com/300x300?text=Package' }}"
                                alt="{{ $package->name }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="font-luxury text-2xl font-bold text-stone-900">{{ $package->name }}</h3>
                            <p class="text-stone-500 text-sm max-w-md line-clamp-1">{{ $package->description }}</p>

                            <div class="flex items-center gap-4 mt-3">
                                <div
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-stone-50 border border-stone-200 text-xs font-bold text-stone-600 uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse(session('booking_step1.event_date'))->format('d M Y') }}
                                </div>
                                <div
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-stone-50 border border-stone-200 text-xs font-bold text-stone-600 uppercase tracking-wider">
                                    <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ session('booking_step1.event_time') }} WIB
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="text-left md:text-right w-full md:w-auto pt-4 md:pt-0 border-t md:border-t-0 border-stone-100">
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-widest mb-1">Total Investasi</p>
                        <div class="text-3xl font-luxury font-bold text-stone-900">
                            Rp {{ number_format($package->price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('client.bookings.store-step2') }}" method="POST" enctype="multipart/form-data"
                id="bookingStep2Form">
                @csrf
                <input type="hidden" name="package_id" value="{{ session('booking_step1.package_id') }}">
                <input type="hidden" name="event_date" value="{{ session('booking_step1.event_date') }}">
                <input type="hidden" name="event_time" value="{{ session('booking_step1.event_time') }}">

                <div class="bg-white rounded-[1.5rem] border border-stone-200 shadow-sm p-6 md:p-10 space-y-8">

                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-xs font-bold text-stone-500 uppercase tracking-widest mb-2 ml-1">Lokasi
                                Acara</label>
                            <textarea name="event_location" rows="3"
                                class="w-full bg-[#FAFAF9] border border-stone-200 rounded-2xl px-5 py-4 text-stone-900 placeholder-stone-400 focus:ring-2 focus:ring-stone-900 focus:border-stone-900 focus:bg-white transition-all resize-none"
                                placeholder="Tuliskan alamat lengkap lokasi pemotretan..." required></textarea>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-stone-500 uppercase tracking-widest mb-2 ml-1">Catatan
                                Tambahan (Opsional)</label>
                            <textarea name="notes" rows="2"
                                class="w-full bg-[#FAFAF9] border border-stone-200 rounded-2xl px-5 py-4 text-stone-900 placeholder-stone-400 focus:ring-2 focus:ring-stone-900 focus:border-stone-900 focus:bg-white transition-all resize-none"
                                placeholder="Contoh: Request pose tertentu, nuansa warna, dll."></textarea>
                        </div>
                    </div>

                    <div class="h-px w-full bg-stone-100"></div>

                    <div>
                        <h3 class="font-luxury text-2xl text-stone-900 mb-6">Instruksi Pembayaran</h3>

                        <div
                            class="bg-gradient-to-br from-[#FAFAF9] to-[#F5F5F4] rounded-2xl p-6 border border-stone-200 relative overflow-hidden mb-8">
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full blur-2xl opacity-60 pointer-events-none">
                            </div>

                            <div class="relative z-10 grid md:grid-cols-2 gap-8">
                                <div>
                                    <div class="flex items-center gap-3 mb-4">
                                        <div
                                            class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-stone-900">Down Payment (DP)</h4>
                                            <p class="text-xs text-stone-500 font-bold uppercase tracking-wider">50%
                                                Wajib Dibayar</p>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-luxury font-bold text-emerald-600 mb-1">
                                        Rp {{ number_format($package->price * 0.5, 0, ',', '.') }}
                                    </div>
                                    <p class="text-sm text-stone-500">Sisa pelunasan dibayar setelah sesi foto selesai.
                                    </p>
                                </div>

                                <div class="bg-white rounded-xl p-5 border border-stone-200 shadow-sm">
                                    <p class="text-xs text-stone-400 font-bold uppercase tracking-widest mb-4">Transfer
                                        Ke</p>

                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            {{-- Logo Bank Placeholder --}}
                                            <div
                                                class="w-10 h-10 rounded bg-blue-50 flex items-center justify-center text-blue-700 font-bold text-xs">
                                                BCA</div>
                                            <div>
                                                <p class="font-bold text-stone-900 text-lg">123-456-7890</p>
                                                <p class="text-xs text-stone-500 uppercase">Memo Potret Studio</p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="navigator.clipboard.writeText('1234567890')"
                                            class="text-stone-400 hover:text-stone-800 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="pt-3 border-t border-stone-100 flex justify-between items-center">
                                        <span class="text-xs text-stone-500">Kode Booking</span>
                                        <span
                                            class="font-mono font-bold text-stone-800 bg-stone-100 px-2 py-1 rounded text-sm">{{ $previewBookingCode }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-stone-500 uppercase tracking-widest mb-3 ml-1">Bukti
                                Transfer</label>

                            <div class="relative group">
                                <input type="file" name="payment_proof" id="payment_proof" class="hidden"
                                    accept="image/*" required>

                                <label for="payment_proof"
                                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-stone-300 rounded-2xl cursor-pointer bg-stone-50 hover:bg-stone-100 hover:border-stone-400 transition-all">
                                    <div id="upload-placeholder"
                                        class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div
                                            class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-stone-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4 4m0 0l-4-4m4 4V4">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="mb-1 text-sm text-stone-600 font-medium"><span
                                                class="text-stone-900 font-bold hover:underline">Klik upload</span> atau
                                            drag & drop</p>
                                        <p class="text-xs text-stone-400">PNG, JPG (Maks. 2MB)</p>
                                    </div>

                                    <div id="filePreview" class="hidden flex items-center gap-3 px-4">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-bold text-stone-800 line-clamp-1" id="fileName">
                                                filename.jpg</p>
                                            <p class="text-xs text-emerald-600 font-bold uppercase tracking-wider">Siap
                                                Upload</p>
                                        </div>
                                    </div>
                                </label>

                                <button type="button" onclick="clearFile()" id="resetBtn"
                                    class="hidden absolute top-3 right-3 p-1 rounded-full bg-red-100 text-red-500 hover:bg-red-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5 flex items-start gap-4">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-rose-800 text-sm mb-1">Kebijakan Pembatalan</h5>
                            <p class="text-xs text-rose-700 leading-relaxed mb-3">
                                Harap diperhatikan bahwa Down Payment (DP) yang sudah dibayarkan <strong>tidak dapat
                                    dikembalikan dengan alasan apapun (non-refundable).</strong>
                            </p>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="terms" id="terms" required
                                    class="rounded-md border-rose-300 text-rose-600 focus:ring-rose-500 cursor-pointer">
                                <span class="text-xs font-bold text-rose-800 group-hover:text-rose-900 transition">Saya
                                    setuju dengan syarat & ketentuan ini.</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse md:flex-row gap-4 pt-4">
                        <a href="{{ route('package.show', $package->id) }}"
                            class="w-full md:w-auto px-8 py-4 bg-white border border-stone-200 text-stone-600 rounded-xl font-bold font-modern text-sm uppercase tracking-widest hover:bg-stone-50 transition text-center">
                            Batal
                        </a>

                        <button type="submit" id="submitBtn" disabled
                            class="flex-1 px-8 py-4 bg-stone-900 text-white rounded-xl font-bold font-modern text-sm uppercase tracking-widest hover:bg-stone-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none flex items-center justify-center gap-3">
                            <span>Konfirmasi Booking</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const fileInput = document.getElementById('payment_proof');
                const filePreview = document.getElementById('filePreview');
                const uploadPlaceholder = document.getElementById('upload-placeholder');
                const fileName = document.getElementById('fileName');
                const resetBtn = document.getElementById('resetBtn');
                const submitBtn = document.getElementById('submitBtn');
                const termsCheckbox = document.getElementById('terms');
                const locationInput = document.querySelector('textarea[name="event_location"]');

                // Handle File Selection
                fileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate Size (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('File terlalu besar! Maksimal 2MB.');
                            this.value = '';
                            clearFileUI();
                            return;
                        }

                        // Validasi Tipe
                        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!validTypes.includes(file.type)) {
                            alert('Format file harus JPG atau PNG.');
                            this.value = '';
                            clearFileUI();
                            return;
                        }

                        // Show UI
                        fileName.textContent = file.name;
                        uploadPlaceholder.classList.add('hidden');
                        filePreview.classList.remove('hidden');
                        filePreview.classList.add('flex');
                        resetBtn.classList.remove('hidden');

                        checkForm();
                    }
                });

                // Clear File Function
                window.clearFile = function () {
                    fileInput.value = '';
                    clearFileUI();
                    checkForm();
                };

                function clearFileUI() {
                    uploadPlaceholder.classList.remove('hidden');
                    filePreview.classList.add('hidden');
                    filePreview.classList.remove('flex');
                    resetBtn.classList.add('hidden');
                }

                // Real-time Validation
                function checkForm() {
                    const isLocationFilled = locationInput.value.trim().length > 0;
                    const isFileUploaded = fileInput.files.length > 0;
                    const isTermsAccepted = termsCheckbox.checked;

                    if (isLocationFilled && isFileUploaded && isTermsAccepted) {
                        submitBtn.disabled = false;
                    } else {
                        submitBtn.disabled = true;
                    }
                }

                locationInput.addEventListener('input', checkForm);
                termsCheckbox.addEventListener('change', checkForm);

                // Handle Submit Confirmation
                document.getElementById('bookingStep2Form').addEventListener('submit', function (e) {
                    const confirmed = confirm("Apakah data sudah benar? DP tidak dapat dikembalikan setelah dikonfirmasi.");
                    if (!confirmed) {
                        e.preventDefault();
                    } else {
                        // Loading State
                        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
                        submitBtn.classList.add('opacity-75', 'cursor-wait');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>