<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Pengaturan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Main Settings -->
                <div class="md:col-span-2">
                    <div class="card mb-6">
                        <h3 class="font-bold text-lg mb-4">Pengaturan Umum</h3>
                        <form method="POST" action="{{ route('owner.settings.update') }}">
                            @csrf
                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm mb-1">Email Kontak</label>
                                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" 
                                           class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Telepon</label>
                                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}" 
                                           class="w-full border rounded px-3 py-2" required>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm mb-1">Persentase DP (%)</label>
                                    <input type="number" name="dp_percentage" value="{{ $settings['dp_percentage'] }}" 
                                           class="w-full border rounded px-3 py-2" min="10" max="100" required>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Batal (hari)</label>
                                    <input type="number" name="cancellation_days" value="{{ $settings['cancellation_days'] }}" 
                                           class="w-full border rounded px-3 py-2" min="1" required>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm mb-1">Instagram</label>
                                    <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] }}" 
                                           class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Facebook</label>
                                    <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] }}" 
                                           class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                            <button type="submit" class="btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar -->
                <div>
                    <div class="card mb-6">
                        <h3 class="font-bold text-lg mb-4">Info Sistem</h3>
                        <div class="space-y-2">
                            <p><span class="text-gray-500">Laravel:</span> {{ app()->version() }}</p>
                            <p><span class="text-gray-500">Timezone:</span> {{ config('app.timezone') }}</p>
                            <p><span class="text-gray-500">Users:</span> {{ \App\Models\User::count() }}</p>
                            <p><span class="text-gray-500">Bookings:</span> {{ \App\Models\Booking::count() }}</p>
                        </div>
                    </div>

                    <div class="card">
                        <h3 class="font-bold text-lg mb-4">Statistik</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="text-center p-3 bg-gray-50 rounded">
                                <div class="text-2xl font-bold">{{ \App\Models\User::where('role', 'client')->count() }}</div>
                                <div class="text-sm text-gray-500">Client</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded">
                                <div class="text-2xl font-bold">{{ \App\Models\Booking::whereMonth('created_at', now()->month)->count() }}</div>
                                <div class="text-sm text-gray-500">Booking Bulan Ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>