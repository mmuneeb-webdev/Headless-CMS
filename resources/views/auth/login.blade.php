{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HeadlessCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-md rounded-lg max-w-md w-full p-8">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Login to HeadlessCMS</h2>

        {{-- Display session errors --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" id="email" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" id="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-blue-600">
                    <span class="text-gray-600">Remember Me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline text-sm">
                    Forgot password?
                </a>
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                Login
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Don’t have an account? 
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
        </p>
    </div>

</body>
</html>
