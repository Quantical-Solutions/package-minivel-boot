<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
    <meta name="referrer" content="origin-when-cross-origin">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="msapplication-tap-highlight" content="no" />
    <title>{{ $type }} Error</title>
    <link rel="icon" href="{{ config('app.url') }}/links/redirect_errors/assets/favicon.png">
    <link rel="stylesheet" href="{{ config('app.url') }}/links/handlers/assets/debugFonts.css"
          type="text/css">
    <link rel="stylesheet" href="{{ config('app.url') }}/links/redirect_errors/assets/style.css"
          type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>
<body data-type="{{ $type }}">
<div id="errorsContainer">
    <h1><span>{{ $type }}</span>{{ $message }}</h1>
</div>
<script type="text/javascript" src="{{ config('app.url') }}/links/redirect_errors/assets/script.js"
></script>
</body>
</html>
