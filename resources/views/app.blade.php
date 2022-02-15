<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="VATSIM ATC Bookings Calendar and API">
    <meta name="author" content="VATSIM Tech Team">
    <title>VATSIM ATC Bookings</title>

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://bootswatch.com/5/darkly/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 75rem;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">VATSIM ATC Bookings</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->path() == '/' ? 'active' : '' }}" href="{{ route('index') }}">7 Day Schedule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->path() == 'api-doc' ? 'active' : '' }}" href="{{ route('api-doc') }}">API Documentation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->path() == 'secret-key-management' ? 'active' : '' }}" href="{{ route('key-management.index') }}">Key Management</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@yield('custom_js')
</body>
</html>
