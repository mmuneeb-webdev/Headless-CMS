<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-2xl p-10 w-full max-w-md text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">
            Welcome, {{ auth()->user()->name }} 🎉
        </h1>

        <p class="text-gray-600 mb-6">
            You are logged in with email: <span class="font-semibold">{{ auth()->user()->email }}</span>
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition"
            >
                Logout
            </button>
        </form>
    </div>

</body>
</html>
