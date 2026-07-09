<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(10);
        return view('admin.messages.index', compact('messages'));
    }

    // Handle frontend submissions
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email',
            'phone'    => 'nullable|string|max:50',
            'address'  => 'nullable|string|max:500',
            'message'  => 'required|string',
            'quantity' => 'nullable|numeric',
        ]);

        $message = Message::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Custom order saved successfully',
            'data'    => $message,
        ]);
    }
}
