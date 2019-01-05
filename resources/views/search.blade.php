<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    </head>
    <body>
        <div class="container">
            <div class="row mt-4">
                <div class="col">
                    <form action="" method="get">
                        <input type="text" name="q" value="{{ request('q') }}">
                        <button>Search</button>
                    </form>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-8">
                    @forelse ($results as $result)
                        <article class="mt-3">
                            <h4>{{$result['title']}}</h4>
                            <p class="well mb-4">{!!$result['highlight']!!}</p>
                        </article>
                    @empty
                        <p>No articles found</p>
                    @endforelse

                        <div class="paginator mt-4">
                            {{$paginator->appends('q', $query)}}
                        </div>

                </div>
            </div>
        </div>
    </body>
</html>
