<x-layout>

    <h1>Payment Receipts</h1>
    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Error Message</th>
                <th>Date</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receipts as $receipt)
                <tr>
                    <td>{{ $receipt->payment_reference }}</td>
                    <td>{{ $receipt->status }}</td>
                    <td>{{ $receipt->amount }}</td>
                    <td>{{ $receipt->error_message }}</td>
                    <td>{{ $receipt->created_at }}</td>
                    <td>
                        <a href="{{ route('receipts.show', $receipt->id) }}">View Receipt</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- {{$receipts->links()}} --}}

</x-layout>


