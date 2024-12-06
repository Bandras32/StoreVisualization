<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Grocery Visualizer</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-b from-green-100 to-green-200 h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white shadow-xl rounded-lg p-6 border border-green-300">
        <div class="text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Grocery Logo" class="mx-auto mb-4 w-24 h-24 rounded-full border border-gray-300">
            <h1 class="text-4xl font-bold text-green-700 mb-4">Üdvözlünk!</h1>
        </div>

        <div class="flex flex-col space-y-4">
            <a href="{{ route('login') }}" class="w-full text-center bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 shadow">
                Belépés
            </a>
            <a href="{{ route('register') }}" class="w-full text-center bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 shadow">
                Regisztráció
            </a>
        </div>
    </div>
</body>
</html>
