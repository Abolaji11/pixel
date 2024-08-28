

<x-layout>
    <h1>Payment for Featured Job Post</h1>
    <p>Amount: {{ $amount }}</p>
    <p>Description: {{ $description }}</p> <br>

    <form method="POST" action="/pay">
        @csrf
        <input type="hidden" name="amount" value="{{ $amount }}">
        {{-- <button type="submit" >Proceed to Payment</button> --}}
        <x-forms.button style="p-5 mt-5"> Proceed to Payment </x-forms.button>

    </form>
</x-layout>
