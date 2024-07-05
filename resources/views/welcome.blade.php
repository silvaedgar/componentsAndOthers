
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Styles -->

</head>

<body class="bg-secondary text-white">
    @if (auth()->check())

    @else
        <b>Sin Loguearse</b>
    @endif

    @yield('content')

</body>

</html>
<script>
    var timeIdle = 0

    window.addEventListener('DOMContentLoaded', resetTimeIdle);

    function resetTimeIdle() {
        console.log("entre al reset")
        timeIdle = 0;
    }

    setInterval(() => {
        timeIdle++;
        if(timeIdle > 10) {
            console.log("tiempo vencido")
        }
    }, 1000);


</script>
