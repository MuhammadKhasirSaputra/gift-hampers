<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Gift & Hampers</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen flex bg-gray-50">

    <!-- Sisi Kiri: Branding (Gradasi) -->
    <div class="hidden md:flex w-1/2 bg-gradient-to-br from-pink-400 via-purple-400 to-pink-500 flex-col items-center justify-center text-white p-10">
        <div class="bg-white/20 p-8 rounded-3xl mb-6 backdrop-blur-sm shadow-2xl">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold mb-3">Gift & Hampers</h1>
        <p class="text-xl mb-2">Sistem Pemesanan Online</p>
        <p class="text-sm opacity-90 text-center max-w-md mt-4">Kelola pemesanan gift dan hampers dengan mudah dan efisien</p>
    </div>

    <!-- Sisi Kanan: Form Login -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
                <p class="text-gray-500">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Demo Login Info -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-xl mb-6 border border-purple-200">
                <p class="font-semibold text-gray-700 mb-2 text-sm">📝 Demo Login:</p>
                <div class="text-xs space-y-1 text-gray-600">
                    <p><span class="font-bold">Admin:</span> admin@giftshop.com / admin123</p>
                    <p><span class="font-bold">User:</span> user@giftshop.com / user123</p>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-gray-400">📧</span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none transition" 
                            placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-gray-400">🔒</span>
                        <input type="password" name="password" required 
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none transition" 
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-purple-600 rounded focus:ring-purple-400">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-pink-500 to-purple-500 text-white font-semibold py-3 rounded-xl hover:shadow-lg hover:scale-[1.02] transition duration-200">
                    Login
                </button>
            </form>
        </div>
    </div>

</body>
</html>