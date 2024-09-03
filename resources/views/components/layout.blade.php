<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>pixel-positions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/pagination.css', 'resources/js/app.js'])
</head>

<body class="bg-black text-white font-hanken-grotesk pb-20">
    <div class="px-10">
        <nav class="flex justify-between items-center py-4 border-b border-white/10">
            <div>
                <a href="/"> <img src="{{ Vite::asset('resources/images/logo.svg') }}" alt=""> </a>
            </div>

            <div class="space-x-8 font-bold">
                <a href="/" class="{{ Request::is('/') ? 'text-blue-500' : '' }}">Jobs</a>
                <a href="/career" class="{{ Request::is('career') ? 'text-blue-500' : '' }}">Careers</a>
                <a href="/salary" class="{{ Request::is('salary') ? 'text-blue-500' : '' }}">Salaries</a>
              
            </div>

            @auth
                <div class="space-x-6 font-bold flex">
                    <a href="/jobs/create" class="{{ Request::is('jobs/create') ? 'text-blue-500' : '' }}">Post a Job</a>
                    <form action="/logout" method="POST">
                        @csrf
                        @method('DELETE')
                        <button>Logout</button>
                    </form>
                </div>
            @endauth

            @guest
                <div class="space-x-6 font-bold">
                    <a href="/register" class="{{ Request::is('register') ? 'text-blue-500' : '' }}">Sign Up</a>
                    <a href="/login" class="{{ Request::is('login') ? 'text-blue-500' : '' }}">Log In</a>
                </div>
            @endguest
        </nav>

        <main class="mt-10 max-w-[986px] mx-auto font-hanken-grotesk">{{ $slot }}</main>
    </div>
</body>

</html>
