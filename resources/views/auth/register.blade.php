<!DOCTYPE html>
<html lang={{ str_replace('_', '-', app()->getLocale()) }}>
    <head>
        <meta charset=utf-8>
        <meta name=viewport content=width=device-width, initial-scale=1>
        <meta name=csrf-token content={{ csrf_token() }}>
        <title>{{ config('app.name', 'ShopNow') }} - Register</title>
        <script src=https://cdn.tailwindcss.com></script>
    </head>
    <body class='bg-gray-100 min-h-screen flex items-center justify-center'>
        <div class='w-full max-w-md px-4'>
            <div class='text-center mb-6'>
                <a href={{ url('/') }} class='text-3xl font-bold text-purple-600'>ShopNow</a>
                <p class='text-gray-500 mt-2'>Create your account to start shopping.</p>
            </div>
            
            <div class='bg-white rounded-lg shadow-md p-8'>
                <form method=POST action={{ route('register') }}>
                    @csrf
                    <div class=mb-4>
                        <label for=name class='block text-sm font-medium text-gray-700 mb-1'>Full Name</label>
                        <input id=name type=text name=name value={{ old('name') }} required autofocus autocomplete=name
                            class='w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror'>
                        @error('name')
                            <p class='text-red-500 text-sm mt-1'>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class=mb-4>
                        <label for=email class='block text-sm font-medium text-gray-700 mb-1'>Email</label>
                        <input id=email type=email name=email value={{ old('email') }} required autocomplete=username
                            class='w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror'>
                        @error('email')
                            <p class='text-red-500 text-sm mt-1'>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class=mb-4>
                        <label for=password class='block text-sm font-medium text-gray-700 mb-1'>Password</label>
                        <input id=password type=password name=password required autocomplete=new-password
                            class='w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password') border-red-500 @enderror'>
                        @error('password')
                            <p class='text-red-500 text-sm mt-1'>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class=mb-6>
                        <label for=password_confirmation class='block text-sm font-medium text-gray-700 mb-1'>Confirm Password</label>
                        <input id=password_confirmation type=password name=password_confirmation required autocomplete=new-password
                            class='w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror'>
                        @error('password_confirmation')
                            <p class='text-red-500 text-sm mt-1'>{{ $message }}</p>
                        @enderror
                    </div>

                    <button type=submit class='w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-200 font-medium'>
                        Register
                    </button>
                </form>

                <div class='mt-6 text-center'>
                    <p class=text-gray-600>Already have an account? 
                        <a href={{ route('login') }} class='text-purple-600 font-medium hover:text-purple-800'>
                            Login
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