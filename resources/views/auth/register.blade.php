<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl p-10 w-full max-w-md">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">
            Create Account
        </h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-600 mb-1">Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-emerald-300"
                >
            </div>

            <div>
                <label class="block text-gray-600 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-emerald-300"
                >
            </div>

            <div>
                <label class="block text-gray-600 mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-emerald-300"
                >
            </div>

            <div>
                <label class="block text-gray-600 mb-1">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-emerald-300"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 rounded-lg transition"
            >
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Already have an account?
            <a href="{{ url('/login') }}" class="text-emerald-600 hover:underline">
                Login
            </a>
        </p>
    </div>

</body>
</html>
