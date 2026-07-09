<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md">
            <div class="p-6 text-xl font-bold border-b">
                Admin Panel
            </div>
            <nav class="p-4 space-y-2">
                <a href="/admin/orders" class="block px-3 py-2 rounded hover:bg-gray-200">Orders</a>
                <a href="/admin/blogs" class="block px-3 py-2 rounded hover:bg-gray-200">Blogs</a>
                <a href="/admin/messages" class="flex items-center px-3 py-2 rounded hover:bg-gray-200">

                    Messages
                    @if(\App\Models\Message::count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ \App\Models\Message::count() }}
                    </span>
                    @endif
                </a>
                <a href="/admin/settings" class="block px-3 py-2 rounded hover:bg-gray-200">Settings</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</body>

</html>