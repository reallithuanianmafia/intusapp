<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ًApplication</title>
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          crossorigin="anonymous">
    </head>
    <body>
        <div id="app">

        </div>

        @vite('resources/js/app.js')

    </body>
</html>