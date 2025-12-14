<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Buat Booking</h2>
            <a href="{{ route('client.dashboard') }}" class="text-sm text-indigo-600">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <form method="POST" action="{{ route('client.bookings.store') }}">
                    @csrf

                    <!-- Package -->
                    <div class="mb-6">
                        <label class="block font-medium mb-2">Pilih Paket</label>
                        <div class="grid md:grid-cols-3 gap-4">
                            @foreach($packages as $package)
                            <label class="border rounded-lg p-4 cursor-pointer hover:border-indigo-500">
                                <input type="radio" name="package_id" value="{{ $package->id }}" 
                                       class="mr-2" {{ old('package_id') == $package->id ? 'checked' : '' }}>
                                <span class="font-medium">{{ $package->name }}</span>
                                <div class="mt-1 text-lg font-bold text-indigo-600">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                            </label>
                            @endforeach
                        </div>
                        @error('package_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Date & Time -->
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block font-medium mb-2">Tanggal Acara</label>
                            <div class="relative">
                                <input type="text" id="event_date" name="event_date" 
                                       class="w-full border rounded px-3 py-2 cursor-pointer" 
                                       placeholder="Pilih tanggal" readonly required
                                       value="{{ old('event_date') }}">
                                <div class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">📅</div>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Tanggal terisi tidak bisa dipilih</p>
                            @error('event_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-medium mb-2">Waktu</label>
                            <div class="relative">
                                <input type="text" id="event_time" name="event_time" 
                                       class="w-full border rounded px-3 py-2 cursor-pointer" 
                                       placeholder="Pilih waktu" readonly required
                                       value="{{ old('event_time') }}">
                                <div class="absolute right-3 top-2.5 text-gray-400 pointer-events-none">⏰</div>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Pilih jam 07:00 - 18:00</p>
                            @error('event_time') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="mb-6">
                        <label class="block font-medium mb-2">Lokasi Acara</label>
                        <textarea name="event_location" rows="2" class="w-full border rounded px-3 py-2" required>{{ old('event_location') }}</textarea>
                        @error('event_location') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block font-medium mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn-primary">Buat Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing flatpickr...');
            
            // 1. DATE PICKER untuk tanggal
            const datePicker = flatpickr("#event_date", {
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: @json($bookedDates),
                locale: "id",
                clickOpens: true,
                allowInput: false,
                onChange: function(selectedDates, dateStr) {
                    console.log('Tanggal dipilih:', dateStr);
                }
            });
            
            console.log('Date picker initialized:', datePicker);
            
            // 2. TIME PICKER untuk waktu (flatpickr khusus waktu)
            const timePicker = flatpickr("#event_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                locale: "id",
                minuteIncrement: 30, // Step 30 menit
                defaultHour: 8,
                defaultMinute: 0,
                minTime: "07:00",
                maxTime: "18:00",
                onChange: function(selectedDates, dateStr) {
                    console.log('Waktu dipilih:', dateStr);
                }
            });
            
            console.log('Time picker initialized:', timePicker);
            
            // 3. Debug: Tambahkan click event listeners
            document.getElementById('event_date').addEventListener('click', function() {
                console.log('Date input clicked');
            });
            
            document.getElementById('event_time').addEventListener('click', function() {
                console.log('Time input clicked');
            });
            
            // 4. Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const date = document.getElementById('event_date').value;
                const time = document.getElementById('event_time').value;
                
                if (!date) {
                    e.preventDefault();
                    alert('Silakan pilih tanggal terlebih dahulu');
                    document.getElementById('event_date').focus();
                    return false;
                }
                
                if (!time) {
                    e.preventDefault();
                    alert('Silakan pilih waktu terlebih dahulu');
                    document.getElementById('event_time').focus();
                    return false;
                }
                
                console.log('Submitting with:', { date, time });
                return true;
            });
        });
    </script>
    @endpush
    
    @push('styles')
    <style>
        /* Style untuk flatpickr */
        .flatpickr-calendar {
            border-radius: 8px !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
            border: 1px solid #e5e7eb !important;
            z-index: 9999 !important;
        }
        
        .flatpickr-day.selected {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
        }
        
        .flatpickr-day.disabled {
            background-color: #fef2f2 !important;
            color: #dc2626 !important;
            text-decoration: line-through;
            cursor: not-allowed !important;
        }
        
        .flatpickr-day.disabled:hover {
            background-color: #fef2f2 !important;
        }
        
        /* Style untuk time picker */
        .flatpickr-time {
            border-radius: 8px !important;
        }
        
        /* Pastikan cursor pointer */
        #event_date, #event_time {
            cursor: pointer !important;
            background-color: white !important;
            caret-color: transparent !important;
        }
        
        /* Hilangkan outline saat focus */
        #event_date:focus, #event_time:focus {
            outline: 2px solid #4f46e5;
            outline-offset: 2px;
        }
    </style>
    @endpush
</x-app-layout>