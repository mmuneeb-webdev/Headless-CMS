<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-100 via-indigo-100 to-purple-100 flex items-center justify-center">

    <!-- Glass Card -->
    <div class="backdrop-blur-xl bg-white/80 rounded-2xl shadow-xl p-10 w-full max-w-md text-center border border-white/40">

        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Welcome back
        </h1>

        <p class="text-gray-600 mb-6">
            {{ auth()->user()->name }} <br>
            <span class="text-sm">{{ auth()->user()->email }}</span>
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 rounded-lg transition"
            >
                Logout
            </button>
        </form>

    </div>

</body>
</html>
