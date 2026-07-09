<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = \App\Models\Order::with('items')->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }



    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'            => 'required|string|max:255',
                'email'           => 'required|email',
                'phone'           => 'required|string',
                'address'         => 'required|string',
                'payment_method'  => 'required|in:cod,bank',
                'items'           => 'required|array|min:1',
                'items.*.name'         => 'required|string',
                'items.*.quantity'     => 'required|integer|min:1',
                'items.*.price'        => 'required|numeric|min:0',
            ]);

            $order = Order::create($data);

            foreach ($data['items'] as $item) {
                $order->items()->create($item);
            }

            // Reload order with items for response
            $order->load('items');

            $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
            $delivery = 250;
            $total = $subtotal + $delivery;

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order' => [
                    'id' => $order->id,
                    'name' => $order->name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'payment_method' => $order->payment_method,
                    'status' => $order->status,
                    'items' => $order->items,
                    'subtotal' => $subtotal,
                    'delivery_charges' => $delivery,
                    'total' => $total,
                    'created_at' => $order->created_at->format('d M, Y h:i A')
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
