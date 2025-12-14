<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Kalender</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600">← Dashboard</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Date Nav -->
            <div class="card mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold">{{ \Carbon\Carbon::parse($date)->translatedFormat('F Y') }}</h3>
                        <p class="text-sm text-gray-500">{{ $bookings->count() }} booking hari ini</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="?date={{ \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d') }}" class="btn-secondary">←</a>
                        <a href="?date={{ now()->format('Y-m-d') }}" class="btn-secondary">Hari Ini</a>
                        <a href="?date={{ \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d') }}" class="btn-secondary">→</a>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Calendar -->
                <div class="lg:col-span-2">
                    <div class="card">
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            @foreach(['M', 'S', 'S', 'R', 'K', 'J', 'S'] as $day)
                            <div class="text-center font-medium text-gray-500">{{ $day }}</div>
                            @endforeach
                        </div>
                        
                        @php
                            $firstDay = \Carbon\Carbon::parse($date)->startOfMonth()->startOfWeek();
                            $lastDay = \Carbon\Carbon::parse($date)->endOfMonth()->endOfWeek();
                            $currentDay = $firstDay->copy();
                        @endphp
                        
                        <div class="grid grid-cols-7 gap-1">
                            @while($currentDay <= $lastDay)
                                @php
                                    $isToday = $currentDay->isToday();
                                    $isCurrentMonth = $currentDay->month == \Carbon\Carbon::parse($date)->month;
                                    $hasEvents = isset($monthEvents[$currentDay->format('Y-m-d')]);
                                    $isSelected = $currentDay->format('Y-m-d') == $date;
                                @endphp
                                
                                <a href="?date={{ $currentDay->format('Y-m-d') }}"
                                   class="h-20 border rounded p-1 hover:bg-gray-50 
                                          {{ $isToday ? 'bg-blue-50 border-blue-200' : '' }}
                                          {{ !$isCurrentMonth ? 'text-gray-400' : '' }}
                                          {{ $isSelected ? 'bg-indigo-50 border-indigo-300' : '' }}">
                                    <div class="flex justify-between">
                                        <span class="text-sm {{ $isToday ? 'font-bold' : '' }}">
                                            {{ $currentDay->day }}
                                        </span>
                                        @if($hasEvents)
                                        <span class="text-xs bg-indigo-100 text-indigo-800 rounded-full px-2">
                                            {{ $monthEvents[$currentDay->format('Y-m-d')]['count'] }}
                                        </span>
                                        @endif
                                    </div>
                                </a>
                                
                                @php $currentDay->addDay(); @endphp
                            @endwhile
                        </div>
                    </div>
                </div>

                <!-- Today's Bookings -->
                <div>
                    <div class="card">
                        <h3 class="font-bold mb-4">Booking {{ \Carbon\Carbon::parse($date)->translatedFormat('d F') }}</h3>
                        
                        @if($bookings->count() > 0)
                        <div class="space-y-3">
                            @foreach($bookings as $booking)
                            <div class="border rounded p-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium">{{ $booking->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ date('H:i', strtotime($booking->event_time)) }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded 
                                        {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100') }}">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                                <p class="text-sm mt-1">{{ $booking->package->name }}</p>
                                <div class="mt-2 flex space-x-2">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-xs text-indigo-600">Detail</a>
                                    <a href="https://wa.me/{{ $booking->user->phone }}" target="_blank" class="text-xs text-green-600">WA</a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-500 text-center py-8">Tidak ada booking</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>