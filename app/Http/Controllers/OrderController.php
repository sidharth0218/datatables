<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $order = Order::create([
    //         'user_id' => $request->user_id,
    //         'order_number' => 'ORD-' . strtoupper(Str::random(8)),
    //         'total_amount' => $request->total_amount,
    //         'status' => 'pending',
    //     ]);

    //     // 2. Call Razorpay API using cURL
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    //         'amount' => $request->total_amount * 100, // Convert to Paise
    //         'currency' => 'INR',
    //         'receipt' => $order->order_number,
    //     ]));
    //     curl_setopt($ch, CURLOPT_USERPWD, env('RAZORPAY_KEY') . ':' . env('RAZORPAY_SECRET'));
        
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    //     $response = json_decode(curl_exec($ch));
    //     curl_close($ch);
            
    //     // 3. Return local order + razorpay order id to frontend
    //     return response()->json([
    //         'local_order' => $order,
    //         'razorpay_order_id' => $response->id
    //     ], 201);
    // }

  public function store(Request $request)
{
    // ... your Order::create code ...
    $order = Order::create([
            'user_id' => $request->user_id,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'total_amount' => $request->total_amount,
            'status' => 'pending',
        ]);
    // FIX: Match the names exactly with your .env file
    $keyId = env('RAZORPAY_KEY_ID'); 
    $keySecret = env('RAZORPAY_KEY_SECRET');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'amount' => $request->total_amount * 100, 
        'currency' => 'INR',
        'receipt' => $order->order_number,
    ]));
    
    // Pass the correct variables here
    curl_setopt($ch, CURLOPT_USERPWD, $keyId . ':' . $keySecret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    // Adding this just in case you are on XAMPP (Windows)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $result = curl_exec($ch);
    $response = json_decode($result);
    curl_close($ch);

    if (isset($response->error)) {
        return response()->json(['error' => $response->error->description], 401);
    }

    return response()->json([
        'local_order' => $order,
        'razorpay_order_id' => $response->id
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
    public function verifyPayment(Request $request)
{
    // FIX: Match the name in your .env
    $secret = env('RAZORPAY_KEY_SECRET'); 
    
    $signature = $request->razorpay_signature;
    $orderId = $request->razorpay_order_id;
    $paymentId = $request->razorpay_payment_id;

    // Manual signature verification
    $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $secret);

    if ($expectedSignature === $signature) {
        // Update status in database
        Order::where('order_number', $request->receipt)->update(['status' => 'paid']);
        
        return response()->json(['message' => 'Payment Verified'], 200);
    }

    return response()->json(['message' => 'Signature Mismatch'], 400);
}
}
