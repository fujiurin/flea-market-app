<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <a href="/">
            <img class="header-logo" src="{{ asset('img/logo/COACHTECHヘッダーロゴ.png') }}" alt="ヘッダーロゴ">
        </a>
    </header>

    <main>
        @yield('content')
    </main>
    
</body>
</html>