<x-layouts.admin>
    <h1 class="text-2xl font-bold mb-4">Orders Dashboard</h1>

    @php
    $groupedOrders = $orders->groupBy(fn($order) => $order->created_at->toDateString());
    @endphp

    <div
        x-data="{
            open: false,
            order: {},
            orders: @js($orders->load('items')),
            showOrder(id) {
                this.order = this.orders.find(o => o.id === id);
                this.open = true;
            }
        }">
        @foreach ($groupedOrders as $date => $ordersForDate)
        <h3 class="text-lg font-semibold text-gray-700 mb-2">
            {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
        </h3>

        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg mb-6">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Customer</th>
                    <th class="py-2 px-4">Email</th>
                    <th class="py-2 px-4">Phone</th>
                    <th class="py-2 px-4">Total</th>
                    <th class="py-2 px-4">Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ordersForDate as $order)
                <tr class="border-t">
                    <td class="py-2 px-4">{{ $order->id }}</td>
                    <td class="py-2 px-4">{{ $order->name }}</td>
                    <td class="py-2 px-4">{{ $order->email }}</td>
                    <td class="py-2 px-4">{{ $order->phone }}</td>
                    <td class="py-2 px-4">
                        Rs. {{ $order->items->sum(fn($item) => $item->price * $item->quantity) + 250 }}
                    </td>
                    <td class="py-2 px-4">
                        <button
                            class="bg-blue-600 px-3 py-1 rounded hover:bg-blue-700"
                            @click="showOrder({{ $order->id }})">
                            View
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach

        <div class="mt-4">
            {{ $orders->links() }}
        </div>

        <!-- Order Details Modal -->
        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            @click="open = false">
            <div
                class="bg-white text-gray-800 rounded-lg shadow-lg p-6 w-1/2"
                @click.stop>
                <h2 class="text-xl font-bold mb-4">Order Details</h2>
                <p><strong>Customer:</strong> <span x-text="order.name"></span></p>
                <p><strong>Address:</strong> <span x-text="order.address"></span></p>
                <p><strong>Phone:</strong> <span x-text="order.phone"></span></p>
                <p><strong>Email:</strong> <span x-text="order.email"></span></p>
                <p><strong>Items:</strong></p>
                <ul class="list-disc ml-6">
                    <template x-for="item in order.items" :key="item.id">
                        <li x-text="`${item.name} (x${item.quantity}) — Rs. ${item.price}`"></li>
                    </template>
                </ul>
                <button class="mt-4 bg-red-500  px-4 py-2 rounded hover:bg-red-600" @click="open = false">
                    Close
                </button>
            </div>
        </div>
    </div>
</x-layouts.admin>