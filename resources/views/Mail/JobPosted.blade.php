<h1> Job Posting Confirmation</h1>

<h2>Hello, {{ Auth::user()->name }}!</h2>

<p>Your job listing for {{ $title }} has been successfully posted.</p>

<h2> Job Details:</h2>
<p> Title: {{ $title }}</p>
<p> Salary: {{ $salary }}</p>
<p> Location: {{ $location }}</p>
<p> Schedule: {{ $schedule }}</p>


<a href="{{ $url }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block;">View your Job Listing</a>
<br>
<br>
<p>You can view your payment receipt by clicking the link below:</p>
<p><a href="{{ $receiptUrl }}" style="background-color: #c63953; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block;"
    >View Payment Receipt</a></p>
    <p>If you have any questions or need further assistance, please don't hesitate to reach out.</p>
    <p>Thank you for using {{ config('app.name') }}!</p>

