<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <link rel="icon" href="{{ asset('favicon-32x32.png') }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ config('app.name') }}</title>
    @vite(['resources/js/app.js'])
</head>

<body>

<div id="app"></div>

</body>
</html>
