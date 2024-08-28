<h1> Job Posting Confirmation</h1>

<h2>Hello, {{ Auth::user()->name }}!</h2>

<p>Your job listing for {{ $title }} has been successfully posted.</p>

<h2> Job Details:</h2>
<p> Title: {{ $title }}</p>
<p> Salary: {{ $salary }}</p>
<p> Location: {{ $location }}</p>
<p> Schedule: {{ $schedule }}</p>

<a href="{{ $url }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block;">View your Job Listing</a>

<p>Thank you for using our platform!</p>

Best regards,<br>
{{ config('app.name') }}
