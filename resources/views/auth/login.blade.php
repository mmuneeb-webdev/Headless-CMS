<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HeadlessCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-100 to-indigo-200 flex items-center justify-center">

    <div class="bg-white/80 backdrop-blur-lg shadow-xl rounded-2xl max-w-md w-full p-8 border border-white/40">

        <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">
            Login to HeadlessCMS
        </h2>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-gray-600">
                    <input type="checkbox" name="remember" class="rounded text-blue-600">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                    Forgot password?
                </a>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium transition">
                Login
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6 text-sm">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">
                Register
            </a>
        </p>
    </div>

</body>
</html>
