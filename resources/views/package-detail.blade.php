<x-guest-layout>
    <!-- Hero -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="mb-6 md:mb-0">
                    <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $package->name }}</h1>
                    <p class="mt-2 text-indigo-100">{{ $package->description }}</p>
                </div>
                <div class="text-center md:text-right">
                    <div class="text-2xl md:text-3xl font-bold text-white">Rp
                        {{ number_format($package->price, 0, ',', '.') }}
                    </div>
                    <div class="text-indigo-200">{{ $package->duration_hours }} jam kerja</div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Left Column: Package Details -->
                <div class="lg:col-span-2">
                    <!-- Package Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-200">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-r 
                                {{ $package->name == 'Paket Gold' ? 'from-amber-500 to-yellow-300' :
    ($package->name == 'Paket Silver' ? 'from-gray-400 to-gray-300' :
        'from-orange-500 to-red-300') }} 
                                flex items-center justify-center text-2xl">
                                @if($package->name == 'Paket Gold')
                                    👑
                                @elseif($package->name == 'Paket Silver')
                                    ✨
                                @else
                                    📸
                                @endif
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $package->name }}</h2>
                                <p class="text-gray-600 mt-1">{{ $package->description }}</p>
                            </div>
                        </div>

                        <!-- Features -->
                        @if(!empty($package->features) && is_array($package->features) && count($package->features) > 0)
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">✨ Fasilitas</h3>
                                <div class="space-y-2">
                                    @foreach($package->features as $feature)
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-gray-700">{{ $feature }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Package Info -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div class="text-center">
                                <div class="text-xl font-bold text-indigo-600">{{ $package->duration_hours }}</div>
                                <div class="text-sm text-gray-600">Jam</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-indigo-600">
                                    @if($package->name == 'Paket Gold') 2 @else 1 @endif
                                </div>
                                <div class="text-sm text-gray-600">Fotografer</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-indigo-600">7</div>
                                <div class="text-sm text-gray-600">Hari Kerja</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-indigo-600">HD</div>
                                <div class="text-sm text-gray-600">Kualitas</div>
                            </div>
                        </div>
                    </div>

                    <!-- Other Packages -->
                    @if($otherPackages->count() > 0)
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Paket Lainnya</h3>
                            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($otherPackages as $other)
                                    <a href="{{ route('package.show', $other) }}"
                                        class="block group border border-gray-200 rounded-xl p-4 hover:border-indigo-300 hover:shadow transition">
                                        <div class="flex items-center gap-3 mb-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-gradient-to-r 
                                                {{ $other->name == 'Paket Gold' ? 'from-amber-500 to-yellow-300' :
                                                    ($other->name == 'Paket Silver' ? 'from-gray-400 to-gray-300' :
                                                    'from-orange-500 to-red-300') }} 
                                                flex items-center justify-center">
                                                @if($other->name == 'Paket Gold')
                                                    <span class="text-sm">👑</span>
                                                @elseif($other->name == 'Paket Silver')
                                                    <span class="text-sm">✨</span>
                                                @else
                                                    <span class="text-sm">📸</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-sm">{{ $other->name }}</h4>
                                                <div class="text-sm font-bold text-indigo-600">Rp
                                                    {{ number_format($other->price, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-600 line-clamp-2">{{ $other->description }}</div>
                                        <div class="mt-3 text-xs text-indigo-600 font-medium group-hover:text-indigo-700">
                                            Lihat detail →
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Booking Form -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8">
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">📅 Booking Sekarang</h3>

                            @auth
                                @if(auth()->user()->isClient())
                                    <form action="{{ route('client.bookings.store') }}" method="POST" id="bookingForm">
                                        @csrf
                                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                                        <!-- Calendar Section -->
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Pilih Tanggal
                                            </label>
                                            <div id="calendar-container" class="border border-gray-300 rounded-lg p-3 mb-3">
                                                <div id="mini-calendar" style="max-width: 100%"></div>
                                            </div>
                                            <input type="hidden" id="event_date" name="event_date" required>
                                            <div id="selected-date" class="text-center p-2 bg-blue-50 rounded text-sm text-blue-700 font-medium hidden">
                                                <span id="selected-date-text"></span>
                                            </div>
                                        </div>

                                        <!-- Time Picker -->
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Waktu Mulai
                                            </label>
                                            <select id="event_time" name="event_time"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                required>
                                                <option value="">Pilih Waktu</option>
                                                @for($i = 7; $i <= 18; $i++)
                                                    @php
                                                        $hour = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                        $times = [$hour . ':00', $hour . ':30'];
                                                    @endphp
                                                    @foreach($times as $time)
                                                        @if($time <= '18:00')
                                                            <option value="{{ $time }}">{{ $time }} WIB</option>
                                                        @endif
                                                    @endforeach
                                                @endfor
                                            </select>
                                        </div>

                                        <!-- Location -->
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Lokasi Acara
                                            </label>
                                            <textarea name="event_location" rows="2"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="Alamat lengkap..." required></textarea>
                                        </div>

                                        <!-- Notes -->
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Catatan (Opsional)
                                            </label>
                                            <textarea name="notes" rows="2"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                                placeholder="Permintaan khusus..."></textarea>
                                        </div>

                                        <!-- Terms & Conditions -->
                                        <div class="mb-6">
                                            <label class="flex items-start gap-2 text-sm text-gray-700">
                                                <input type="checkbox" name="terms" id="terms" required
                                                    class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span>Saya setuju dengan 
                                                    <a href="#" class="text-indigo-600 hover:underline">Syarat & Ketentuan</a>
                                                    dan 
                                                    <a href="#" class="text-indigo-600 hover:underline">Kebijakan Privasi</a>
                                                </span>
                                            </label>
                                        </div>

                                        <!-- Price Summary -->
                                        <div class="border-t border-gray-200 pt-4 mb-6">
                                            <div class="space-y-2">
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">Harga Paket</span>
                                                    <span class="font-medium">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between text-xs text-gray-500">
                                                    <span>DP 50% untuk konfirmasi booking</span>
                                                    <span>Rp {{ number_format($package->price * 0.5, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="border-t pt-2">
                                                    <div class="flex justify-between font-bold">
                                                        <span>Total</span>
                                                        <span class="text-lg text-indigo-600">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1">*Pembayaran setelah booking dikonfirmasi</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" id="submit-btn"
                                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-3 px-6 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow disabled:opacity-50 disabled:cursor-not-allowed"
                                            disabled>
                                            📋 Buat Booking
                                        </button>

                                        <p class="text-xs text-gray-500 text-center mt-3">
                                            Booking akan diproses setelah konfirmasi admin
                                        </p>
                                    </form>
                                @else
                                    <!-- For Admin/Owner -->
                                    <div class="text-center py-6">
                                        <div class="text-4xl mb-3">🔒</div>
                                        <h4 class="font-bold text-gray-900 mb-2">Hanya untuk Client</h4>
                                        <p class="text-gray-600 mb-4">Anda login sebagai {{ auth()->user()->role }}</p>
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('admin.dashboard') }}" class="btn-primary text-sm">
                                                Ke Dashboard Admin
                                            </a>
                                        @elseif(auth()->user()->isOwner())
                                            <a href="{{ route('owner.dashboard') }}" class="btn-primary text-sm">
                                                Ke Dashboard Owner
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <!-- For Guest -->
                                <div class="text-center py-6">
                                    <div class="text-3xl mb-3">🔐</div>
                                    <h4 class="font-bold text-gray-900 mb-2">Login untuk Booking</h4>
                                    <p class="text-gray-600 mb-4">Daftar atau login untuk memesan paket ini</p>
                                    <div class="space-y-2">
                                        <a href="{{ route('register') }}" class="btn-primary w-full text-sm">
                                            ✨ Daftar Akun Baru
                                        </a>
                                        <a href="{{ route('login') }}" class="btn-secondary w-full text-sm">
                                            🔐 Login Akun
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </div>

                        <!-- Contact Info -->
                        <div class="mt-4 bg-gray-50 rounded-xl p-4">
                            <h4 class="font-bold text-gray-900 mb-3">📞 Butuh Bantuan?</h4>
                            <div class="space-y-2">
                                <a href="https://wa.me/6281234567890" target="_blank"
                                    class="flex items-center gap-2 p-2 bg-white rounded hover:shadow-sm transition">
                                    <span class="text-lg">💬</span>
                                    <div class="text-sm">
                                        <div class="font-medium">Chat WhatsApp</div>
                                        <div class="text-xs text-gray-500">Fast response</div>
                                    </div>
                                </a>
                                <div class="flex items-center gap-2 p-2">
                                    <span class="text-lg">📧</span>
                                    <div class="text-sm">
                                        <div class="font-medium">Email</div>
                                        <div class="text-xs text-gray-500">info@memopotret.com</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Calendar Styling */
            .flatpickr-calendar {
                width: 100% !important;
                max-width: 320px !important;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
                border-radius: 12px !important;
                border: 1px solid #e5e7eb !important;
            }

            .flatpickr-day.booked {
                background-color: #fecaca !important;
                color: #dc2626 !important;
                border-color: #fecaca !important;
                text-decoration: line-through;
                cursor: not-allowed !important;
            }

            .flatpickr-day.booked:hover {
                background-color: #fecaca !important;
                color: #dc2626 !important;
            }

            .flatpickr-day.selected {
                background-color: #4f46e5 !important;
                border-color: #4f46e5 !important;
                color: white !important;
            }

            .flatpickr-day.today {
                border-color: #8b5cf6 !important;
                color: #8b5cf6 !important;
            }

            .flatpickr-weekdays {
                background-color: #f9fafb !important;
                border-radius: 8px 8px 0 0 !important;
            }

            .flatpickr-weekday {
                color: #6b7280 !important;
                font-weight: 600 !important;
            }

            .flatpickr-months {
                padding: 8px 0 !important;
            }

            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }

            ::-webkit-scrollbar-thumb {
                background: #c7d2fe;
                border-radius: 3px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: #a5b4fc;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Wait for everything to load
            window.addEventListener('load', function () {
                console.log('🚀 Starting calendar initialization...');

                // Debug: Check if flatpickr is loaded
                console.log('✅ Flatpickr available:', typeof flatpickr !== 'undefined');
                console.log('✅ Flatpickr locale id:', flatpickr.l10ns?.id ? 'Yes' : 'No');

                // Check if element exists
                const calendarElement = document.getElementById('mini-calendar');
                console.log('✅ Calendar element exists:', calendarElement !== null);

                if (!calendarElement) {
                    console.error('❌ ERROR: #mini-calendar element not found!');
                    return;
                }

                // Get booked dates from PHP
                const bookedDates = @json($bookedDates ?? []);
                console.log('📅 Booked dates:', bookedDates);

                try {
                    // Initialize Flatpickr
                    console.log('🔧 Initializing flatpickr...');

                    const calendar = flatpickr("#mini-calendar", {
                        inline: true,
                        dateFormat: "Y-m-d",
                        minDate: "today",
                        locale: "id",  // Use Indonesian locale
                        disable: bookedDates,
                        onChange: function (selectedDates, dateStr) {
                            console.log('📆 Date selected:', dateStr);

                            if (dateStr) {
                                // Set hidden input
                                document.getElementById('event_date').value = dateStr;

                                // Show selected date
                                const dateDisplay = document.getElementById('selected-date');
                                const dateText = document.getElementById('selected-date-text');

                                const date = new Date(dateStr);
                                const formattedDate = date.toLocaleDateString('id-ID', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });

                                dateText.textContent = formattedDate;
                                dateDisplay.classList.remove('hidden');

                                console.log('📋 Date formatted:', formattedDate);
                                checkFormCompletion();
                            }
                        },
                        onDayCreate: function (dObj, dStr, fp, dayElem) {
                            // Mark booked dates
                            const dateStr = fp.formatDate(dayElem.dateObj, "Y-m-d");
                            if (bookedDates.includes(dateStr)) {
                                dayElem.classList.add("booked");
                                dayElem.title = "Tanggal sudah dibooking";
                            }
                        }
                    });

                    console.log('🎉 SUCCESS: Calendar initialized!', calendar);

                    // Add time picker handler
                    const timeSelect = document.getElementById('event_time');
                    if (timeSelect) {
                        timeSelect.addEventListener('change', checkFormCompletion);
                        console.log('✅ Time select handler added');
                    }

                    // Add terms checkbox handler
                    const termsCheckbox = document.getElementById('terms');
                    if (termsCheckbox) {
                        termsCheckbox.addEventListener('change', checkFormCompletion);
                        console.log('✅ Terms checkbox handler added');
                    }

                    // Form completion check function
                    function checkFormCompletion() {
                        const dateSelected = document.getElementById('event_date').value;
                        const timeSelected = document.getElementById('event_time').value;
                        const termsAccepted = document.getElementById('terms')?.checked || false;
                        const submitBtn = document.getElementById('submit-btn');

                        console.log('📋 Checking form - Date:', dateSelected, 'Time:', timeSelected, 'Terms:', termsAccepted);

                        if (submitBtn) {
                            const isComplete = dateSelected && timeSelected && termsAccepted;
                            submitBtn.disabled = !isComplete;
                            console.log('🔄 Submit button:', isComplete ? 'ENABLED' : 'DISABLED');
                        }
                    }

                    // Add form validation
                    const bookingForm = document.getElementById('bookingForm');
                    if (bookingForm) {
                        bookingForm.addEventListener('submit', function (e) {
                            const date = document.getElementById('event_date').value;
                            const time = document.getElementById('event_time').value;
                            const location = document.querySelector('textarea[name="event_location"]').value;
                            const terms = document.getElementById('terms').checked;

                            // Validation checks
                            if (!date) {
                                e.preventDefault();
                                alert('❌ Silakan pilih tanggal terlebih dahulu');
                                return false;
                            }

                            if (!time) {
                                e.preventDefault();
                                alert('❌ Silakan pilih waktu terlebih dahulu');
                                return false;
                            }

                            if (!location || !location.trim()) {
                                e.preventDefault();
                                alert('❌ Silakan isi lokasi acara');
                                document.querySelector('textarea[name="event_location"]')?.focus();
                                return false;
                            }

                            if (!terms) {
                                e.preventDefault();
                                alert('❌ Anda harus menyetujui Syarat & Ketentuan');
                                document.getElementById('terms')?.focus();
                                return false;
                            }

                            // Confirmation dialog
                            const confirmed = confirm(`Konfirmasi Booking:\n\n📅 Tanggal: ${date}\n⏰ Waktu: ${time}\n📦 Paket: {{ $package->name }}\n💰 Total: Rp {{ number_format($package->price, 0, ',', '.') }}\n\nBooking akan menunggu konfirmasi admin dan instruksi pembayaran DP.\n\nLanjutkan?`);

                            if (!confirmed) {
                                e.preventDefault();
                                return false;
                            }

                            // Show loading state
                            const submitBtn = document.getElementById('submit-btn');
                            if (submitBtn) {
                                submitBtn.disabled = true;
                                submitBtn.innerHTML = `
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memproses...
                                    </div>
                                `;
                            }

                            return true;
                        });

                        console.log('✅ Form validation added');
                    }

                    // Initial check
                    checkFormCompletion();
                    console.log('✅ Initial form check completed');

                } catch (error) {
                    console.error('💥 ERROR initializing calendar:', error);
                    console.error('Error stack:', error.stack);

                    // Fallback: Show error message
                    const container = document.getElementById('calendar-container');
                    if (container) {
                        container.innerHTML = `
                            <div class="text-center p-4 border border-red-300 rounded bg-red-50">
                                <p class="text-red-600 font-medium mb-2">⚠️ Kalender tidak dapat dimuat</p>
                                <input type="date" id="fallback-date" class="border rounded px-3 py-2 w-full mb-2" 
                                       min="${new Date().toISOString().split('T')[0]}" 
                                       onchange="document.getElementById('event_date').value = this.value; checkFormCompletion()">
                                <p class="text-sm text-gray-600">Gunakan input tanggal manual</p>
                            </div>
                        `;
                    }
                }
            });

            // Make checkFormCompletion available globally for fallback
            window.checkFormCompletion = function () {
                const dateSelected = document.getElementById('event_date')?.value;
                const timeSelected = document.getElementById('event_time')?.value;
                const termsAccepted = document.getElementById('terms')?.checked || false;
                const submitBtn = document.getElementById('submit-btn');

                if (submitBtn) {
                    submitBtn.disabled = !(dateSelected && timeSelected && termsAccepted);
                }
            };
        </script>
    @endpush
</x-guest-layout>