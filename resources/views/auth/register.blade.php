<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-indigo-100 to-purple-200 flex items-center justify-center">

    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-10 w-full max-w-md border border-white/40">

        <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">
            Create Account
        </h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700 mb-1 font-medium">Name</label>
                <input type="text" name="name" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-gray-700 mb-1 font-medium">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-gray-700 mb-1 font-medium">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-gray-700 mb-1 font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg
                           focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Already have an account?
            <a href="{{ url('/login') }}" class="text-indigo-600 font-medium hover:underline">
                Login
            </a>
        </p>
    </div>

</body>
</html>
