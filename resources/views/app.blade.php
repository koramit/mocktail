<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@300;600&display=swap" rel="stylesheet">

    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>
</head>

<body class="m-0 font-krub font-light text-gray-600 bg-soft-theme-light">
    @inertia

        <div id="page-loading-indicator"
            style="height: 100vh; display: flex; align-items: center; justify-content: center;">
            <svg style="width: 150px; height: 150px;" class="floating-x text-bitter-theme-light" viewBox="0 0 576 512">
                <path fill="currentColor"
                    d="M296 464h-56V338.78l168.74-168.73c15.52-15.52 4.53-42.05-17.42-42.05H24.68c-21.95 0-32.94 26.53-17.42 42.05L176 338.78V464h-56c-22.09 0-40 17.91-40 40 0 4.42 3.58 8 8 8h240c4.42 0 8-3.58 8-8 0-22.09-17.91-40-40-40zM432 0c-62.61 0-115.35 40.2-135.18 96h52.54c16.65-28.55 47.27-48 82.64-48 52.93 0 96 43.06 96 96s-43.07 96-96 96c-14.04 0-27.29-3.2-39.32-8.64l-35.26 35.26C379.23 279.92 404.59 288 432 288c79.53 0 144-64.47 144-144S511.53 0 432 0z">
                </path>
            </svg>
        </div>
</body>

</html>