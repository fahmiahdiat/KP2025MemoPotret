<!-- Modal Backdrop -->
<div id="authModalBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden"></div>

<!-- Modal Container -->
<div id="authModal" class="fixed inset-0 z-[9999] items-center justify-center p-4 hidden">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto overflow-hidden">
        <!-- Close Button -->
        <button id="closeAuthModal" 
                class="absolute right-4 top-4 text-gray-400 hover:text-gray-600 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <!-- Tabs -->
        <div class="border-b">
            <div class="flex">
                <button id="loginTab" 
                        class="flex-1 py-4 font-semibold text-center border-b-2 border-indigo-600 text-indigo-600">
                    Login
                </button>
                <button id="registerTab" 
                        class="flex-1 py-4 font-semibold text-center border-b-2 border-transparent text-gray-500">
                    Daftar
                </button>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="p-6">
            <!-- Login Form (Default) -->
            <div id="loginContent" class="space-y-4">
                <x-auth-session-status class="mb-4" :status="session('status')" />
                
                <form method="POST" action="{{ route('login') }}" id="modalLoginForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="modalEmail" :value="__('Email')" />
                            <x-text-input id="modalEmail" class="block w-full" type="email" name="email" 
                                        :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="modalPassword" :value="__('Password')" />
                            <x-text-input id="modalPassword" class="block w-full" type="password" 
                                        name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                            </label>
                            
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                Lupa password?
                            </a>
                            @endif
                        </div>
                        
                        <x-primary-button class="w-full justify-center">
                            {{ __('Login') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
            
            <!-- Register Form (Hidden) -->
            <div id="registerContent" class="space-y-4 hidden">
                <form method="POST" action="{{ route('register') }}" id="modalRegisterForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="modalName" :value="__('Nama Lengkap')" />
                            <x-text-input id="modalName" class="block w-full" type="text" name="name" 
                                        :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="modalRegisterEmail" :value="__('Email')" />
                            <x-text-input id="modalRegisterEmail" class="block w-full" type="email" name="email" 
                                        :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="modalPhone" :value="__('Nomor WhatsApp')" />
                            <x-text-input id="modalPhone" class="block w-full" type="tel" name="phone" 
                                        :value="old('phone')" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="modalRegisterPassword" :value="__('Password')" />
                            <x-text-input id="modalRegisterPassword" class="block w-full" type="password" 
                                        name="password" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <div>
                            <x-input-label for="modalPasswordConfirmation" :value="__('Konfirmasi Password')" />
                            <x-text-input id="modalPasswordConfirmation" class="block w-full" type="password" 
                                        name="password_confirmation" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        
                        <input type="hidden" name="role" value="client">
                        
                        <x-primary-button class="w-full justify-center">
                            {{ __('Daftar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Switch Text -->
        <div class="px-6 py-4 bg-gray-50 text-center border-t">
            <p id="switchToRegister" class="text-sm text-gray-600">
                Belum punya akun? 
                <button class="text-indigo-600 font-semibold hover:text-indigo-800">Daftar di sini</button>
            </p>
            <p id="switchToLogin" class="text-sm text-gray-600 hidden">
                Sudah punya akun? 
                <button class="text-indigo-600 font-semibold hover:text-indigo-800">Login di sini</button>
            </p>
        </div>
    </div>
</div>