<x-layouts.admin>
    <h1 class="text-2xl font-bold mb-4">Messages</h1>

    <div x-data="{ open: false, message: {} }">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Name</th>
                    <th class="py-2 px-4">Email</th>
                    <th class="py-2 px-4">Phone</th>
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $msg)
                <tr class="border-t">
                    <td class="py-2 px-4">{{ $msg->id }}</td>
                    <td class="py-2 px-4">{{ $msg->name }}</td>
                    <td class="py-2 px-4">{{ $msg->email }}</td>
                    <td class="py-2 px-4">{{ $msg->phone }}</td>
                    <td class="py-2 px-4">{{ $msg->created_at->format('d M, Y h:i A') }}</td>
                    <td class="py-2 px-4">
                        <button
                            class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700"
                            @click="open = true; message = {{ json_encode($msg) }}">
                            View
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $messages->links() }}
        </div>

        <!-- Modal -->
        <div
            x-show="open"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
            @click.away="open = false">
            <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
                <h2 class="text-xl font-bold mb-4">Message Details</h2>
                <p><strong>Name:</strong> <span x-text="message.name"></span></p>
                <p><strong>Email:</strong> <span x-text="message.email"></span></p>
                <p><strong>Phone:</strong> <span x-text="message.phone"></span></p>
                <p><strong>Address:</strong> <span x-text="message.address"></span></p>
                <p><strong>Message:</strong></p>
                <p class="mt-2 text-gray-700" x-text="message.message"></p>
                <button class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" @click="open = false">
                    Close
                </button>
            </div>
        </div>
    </div>
</x-layouts.admin>