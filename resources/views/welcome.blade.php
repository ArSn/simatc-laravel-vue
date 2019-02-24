<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sim ATC</title>

        <link href="/css/app.css" rel="stylesheet" type="text/css">
        <script src="/js/app.js"></script>

    </head>
    <body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
        <a class="navbar-brand" href="#">Sim ATC</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Help</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">FAQ</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Sign In</a>
                </li>
            </ul>
        </div>
    </nav>

    <main role="main" id="main" class="container">
        <div class="row">
            <div class="col-md-2">Stats</div>
            <div class="col-md-10">
                <map-zoom-button lat="52.55835117305416" lng="13.289337158203127" :zoom="13.5">Airport</map-zoom-button>
                <map-zoom-button lat="52.558223266446674" lng="13.288771814370538" :zoom="14.5">Tower</map-zoom-button>
                <map-zoom-button lat="52.55779899222007" lng="13.287973956814277" :zoom="14.7">Ground</map-zoom-button>
                <map-zoom-button lat="52.55411015638913" lng="13.292843171815557" :zoom="15.8">Apron</map-zoom-button>
                <map-zoom-button lat="52.554402829246676" lng="13.290372663505071" :zoom="16.9">Terminal A</map-zoom-button>
                <map-zoom-button lat="52.55538161579782" lng="13.296191097682016" :zoom="16.9">Terminal C</map-zoom-button>
                <map-zoom-button lat="52.552261897301264" lng="13.291415024514698" :zoom="16.9">Terminal D</map-zoom-button>
                <map-component></map-component>
            </div>
        </div>
    </main>

    <footer class="container border-top pt-2 fixed-bottom">
        <p>Happily running on open source</p>
    </footer>

    </body>
</html>
