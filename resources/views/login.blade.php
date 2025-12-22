<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jaya Prana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Opsi: Ganti background jika Anda punya gambar yang sesuai */
        /*
        body {
            background-image: url('url_gambar_gudang_atau_latar_belakang_halus.jpg');
            background-size: cover;
        }
        */
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md p-8 space-y-8 bg-white shadow-xl rounded-lg">
        
        <div class="text-center">
            <a href="/" class="inline-block mb-4">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h4a2 2 0 002-2V7a2 2 0 00-2-2h-4a2 2 0 00-2 2z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h.01M7 10h.01M17 10h.01M21 10h.01M3 14h.01M7 14h.01M17 14h.01M21 14h.01M3 18h.01M7 18h.01M17 18h.01M21 18h.01" />
                </svg> --}}
            </a>
            <h2 class="text-2xl font-bold text-gray-900">
                Sistem Inventori Barang 
            </h2>
            <h1>
                <span class="text-3xl font-extrabold text-emerald-600">Jaya Prana</span>
            </h1>
            <p class="mt-2 text-sm text-gray-600">
                Silakan masuk untuk melanjutkan ke dashboard
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="hidden" name="remember" value="true">
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="Alamat Email" value="{{ old('email') }}">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember" type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Ingat Saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Lupa password?
                    </a>
                </div>
                @endif
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evend" />
                        </svg>
                    </span>
                    Masuk
                </button>
            </div>
        </form>
        
        @if (Route::has('register'))
            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Daftar sekarang
                    </a>
                </p>
            </div>
        @endif
        <p class="mt-8 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Sistem Inventori Jaya Prana.
        </p>
    </div>

</body>
</html>