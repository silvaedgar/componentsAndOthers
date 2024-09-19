<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">


    <!-- Styles -->
    {{-- <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
    <link href="{{ asset('css') }}/style-menu-responsive.css" rel="stylesheet"> --}}
    @yield('css')
</head>

<body style="background-color: {{ isset($bgBody) ? $bgBody: '#f0f0f0'}}">
    <div class="content">
        @auth
            @include('layouts.navbar.navbar')
        @else
            @include('layouts.navbar.not-logged')
        @endauth
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    {{-- <script src="{{asset('js')}}/my-menu.js"></script> --}}
    <script>

        var timeWaitAnswerExtended = 10
        var leadTimeForClosure = 30 // 30 segundos antes del cierre avisa la extension
        var timeIdle = 0
        var questionWas = false;
        var closeSession = false;
        var lifetime = "{{ config('session.lifetime')  }}"

        async function closeFormSession(isCloseAutomatico) {
            try {
                const response = await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.head.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                });
                if (!response.ok) {
                    throw new Error(`Logout failed with status: ${response.status}`);
                }

                if (isCloseAutomatico) {
                    alert("La sesion ha finalizado");
                }

                window.location.href = "{{ route('home') }}";
            } catch (error) {
                alert("Error:", error.message); // Handle errors gracefully
            }
        }

        function lclick(object) {
            if (confirm("Seguro que desea Cerrar la Sesion ? "))
                closeFormSession(false)
        }


        window.addEventListener('DOMContentLoaded', resetTimeIdle(false));

        function resetTimeIdle(regenerarSession = false) {
            timeIdle = 0;
            questionWas = false;
            if (regenerarSession) {
                try {
                    fetch('/extendedSession', {
                            method: 'GET',
                            headers: {
                                "Content-Type": "application/json",
                            },
                        })
                        .then(resp => {
                            console.log("RESP: ", resp)
                            return resp.json();
                        })
                } catch (error) {
                    alert("Error ", error);
                }

            }
        }

        function verifyExtendSession() {
            let startTime = new Date();
            const extendedSession =  confirm("Desea Extender la Sesion");
            let endTime = new Date();
            let diffMS = (endTime - startTime) / 1000  // verifica que no se haya tardado en responder
            timeIdle += diffMS // suma el tiempo de espera para la respuesta al tiempo de vencimiento de la sesion
            if (extendedSession && diffMS < timeWaitAnswerExtended ) resetTimeIdle(true)
        }

        setInterval(() => {
            is_logged = "{{ auth()->check()  }}"
            timeIdle++;
            timeWait = lifetime * 60 - leadTimeForClosure
            timeWaitClose = lifetime * 60

            if(timeIdle > timeWait && !questionWas && is_logged != '') {
                questionWas = true
                verifyExtendSession();
            }
            if (timeIdle > timeWaitClose && is_logged != '') {
                closeFormSession(true)
                resetTimeIdle()
            }
        }, 1000);
    </script>
    @stack('js')
</body>

</html>
