 @extends('layouts.app')

@section('content')
<button id="buy-now">Buy Now</button>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('buy-now').onclick = async function() {
    try {
        // 1. Create Order
        const res = await fetch("{{ route('orders.store') }}", {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // ADD THIS LINE
            },
            body: JSON.stringify({ 
                user_id: 1, 
                total_amount: 500 
            })
        });

        if (!res.ok) throw new Error('Order creation failed');
        const data = await res.json();

        // 2. Open Razorpay
        const options = {
            "key": "{{ env('RAZORPAY_KEY_ID') }}", 
            "amount": data.local_order.total_amount * 100,
            "order_id": data.razorpay_order_id,
            "handler": async function (response) {
                // 3. Verify on server
                const verifyRes = await fetch("{{ route('orders.verify') }}", {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // ADD THIS LINE
                    },
                    body: JSON.stringify({
                        ...response,
                        receipt: data.local_order.order_number
                    })
                });
                
                if (verifyRes.ok) {
                    alert("Payment Success!");
                    window.location.reload();
                }
            }
        };
        new Razorpay(options).open();
    } catch (err) {
        console.error(err);
        alert("Something went wrong. Check console.");
    }
};
</script>
    
@endsection