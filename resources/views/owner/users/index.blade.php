<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Kelola User</h2>
                <p class="text-sm text-gray-500 mt-1">Manajemen admin dan akses sistem</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('owner.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <a href="{{ route('owner.users.create-admin') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Admin
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Admin</p>
                            <p class="text-2xl font-bold text-indigo-600">{{ $admins->count() }}</p>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-lg">
                            <i class="fas fa-user-shield text-indigo-600"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="text-xs text-gray-500">
                            @php
                                $activeCount = $admins->where('is_active', true)->count();
                                $inactiveCount = $admins->where('is_active', false)->count();
                            @endphp
                            {{ $activeCount }} aktif • {{ $inactiveCount }} nonaktif
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Admin Aktif</p>
                            <p class="text-2xl font-bold text-green-600">{{ $activeCount }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="text-xs text-gray-500">Sedang dapat akses sistem</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Admin Nonaktif</p>
                            <p class="text-2xl font-bold text-amber-600">{{ $inactiveCount }}</p>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-lg">
                            <i class="fas fa-ban text-amber-600"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="text-xs text-gray-500">Akses sistem ditangguhkan</div>
                    </div>
                </div>
            </div>

            <!-- Admins Table -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Admin</h3>
                            <p class="text-sm text-gray-500 mt-1">Manajemen hak akses administrator</p>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   placeholder="Cari admin..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full md:w-64 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   onkeyup="filterAdmins(this.value)">
                        </div>
                    </div>
                </div>

                @if($admins->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($admins as $admin)
                                <tr class="hover:bg-gray-50 admin-row" 
                                    data-name="{{ strtolower($admin->name) }}" 
                                    data-email="{{ strtolower($admin->email) }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-indigo-600 font-bold">
                                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $admin->name }}</div>
                                                <div class="text-xs text-gray-500">ID: {{ $admin->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $admin->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $admin->phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $admin->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $admin->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($admin->is_active ?? true)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1.5"></i>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                <i class="fas fa-ban mr-1.5"></i>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('owner.users.toggle-status', $admin) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('{{ $admin->is_active ?? true ? 'Nonaktifkan admin ini?' : 'Aktifkan admin ini?' }}')"
                                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg 
                                                           {{ $admin->is_active ?? true ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                                                <i class="fas {{ $admin->is_active ?? true ? 'fa-user-slash' : 'fa-user-check' }} mr-1.5"></i>
                                                {{ $admin->is_active ?? true ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Menampilkan {{ $admins->count() }} admin
                            </div>
                        </div>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-user-shield text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada admin</h3>
                        <p class="text-sm text-gray-500 mb-4">Tambahkan admin baru untuk mengelola sistem</p>
                        <a href="{{ route('owner.users.create-admin') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Admin Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function filterAdmins(searchTerm) {
            const rows = document.querySelectorAll('.admin-row');
            searchTerm = searchTerm.toLowerCase();
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>