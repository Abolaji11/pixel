<x-layout>



    <div class="container">
        <h2 style="background-color: #0066ff; color: white; padding: 10px; margin-bottom: 20px; border-radius: 5px; flex">Payment Receipt Details</h2><br>

        <div class="card">
            <div class="card-header">
                Receipt Reference: {{ $receipt->payment_reference }}
            </div>
            <div class="card-body"> 
               
                <p><strong>Customer:</strong> {{ $receipt->user->name }}</p><br>
                <p><strong>Payment Description:</strong> {{ $receipt->description }}</p><br>
                <p><strong>Payment Method:</strong> {{ ucfirst(strtolower ($receipt->payment_method)) }}</p><br>
                <p><strong>Payment Status:</strong> {{ ucfirst(strtolower ($receipt->payment_status)) }}</p><br>
                <p><strong>Amount:</strong> ₦{{ number_format($receipt->amount, 2) }}</p><br>
                <p><strong>Payment Date:</strong> {{ $receipt->payment_date ? $receipt->payment_date->format('d-m-Y H:i:s') : 'N/A' }}</p><br>
            </div>
        </div>

          
            <a href="{{ auth()->user()->isAdmin() ? route('receipts.index') : url('/') }}" class="btn btn-primary mt-3" style="background-color: #0066ff; color: white; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
            Back to Receipts
            </a>
    </div>



</x-layout>