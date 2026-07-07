<!DOCTYPE html>
<html lang={{ str_replace('_', '-', app()->getLocale()) }}>
    <head>
        <meta charset=utf-8>
        <meta name=viewport content=width=device-width, initial-scale=1>
        <meta name=csrf-token content={{ csrf_token() }}>
        <title>{{ config('app.name', 'ShopNow') }} - Login</title>
        <script src=https://cdn.tailwindcss.com></script>
    </head>
    <body class='bg-gray-100 min-h-screen flex items-center justify-center'>
        <div class='w-full max-w-md px-4'>
            <div class='text-center mb-6'>
                <a href={{ url('/') }} class='text-3xl font-bold text-purple-600'>ShopNow</a>
                <p class='text-gray-500 mt-2'>Welcome back! Please login to your account.</p>
            </div>
            
            <div class='bg-white rounded-lg shadow-md p-8'>
                @if (session('status'))
                    <div class='mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded'>
                        {{ session('status') }}
                    </div>
                @endif

                <form method=POST action={{ route('login') }}>
                    @csrf
                    <div class=mb-4>
                        <label for=email class='block text-sm font-medium text-gray-700 mb-1'>Email</label>
                        <input id=email type=email name=email value={{ old('email') }} required autofocus autocomplete=username 
                            class='w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror'>
                        @error('email')
                            <p class='text-red-500 text-sm mt-1'>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class=mb-4>
                        <label for=password class='block text-sm font-medium text-gray-700 mb-1'>Password</label>
                        <input id=password type=password name=password required autocomplete=current-password
                            class='w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password') border-red-500 @enderror'>
                        @error('password')
                            <p class='text-red-500 text-sm mt-1'>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class='flex items-center justify-between mb-6'>
                        <label class=inline-flex items-center>
                            <input type=checkbox name=remember id=remember_me class='rounded border-gray-300 text-purple-600 focus:ring-purple-500'>
                            <span class='ml-2 text-sm text-gray-600'>Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class='text-sm text-purple-600 hover:text-purple-800' href={{ route('password.request') }}>
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type=submit class='w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-200 font-medium'>
                        Login
                    </button>
                </form>

                <div class='mt-6 text-center'>
                    <p class=text-gray-600>Don't have an account? 
                        <a href={{ route('register') }} class='text-purple-600 font-medium hover:text-purple-800'>
                            Register
                        </a>
                    </p>
                </div>
            </div>

            <div class='mt-4 text-center'>
                <a href={{ url('/') }} class='text-gray-500 hover:text-gray-700 text-sm'>
                    ← Back to Home
                </a>
            </div>
        </div>
    </body>
</html>