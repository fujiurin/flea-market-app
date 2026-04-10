<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <a href="/">
                <img class="header-logo" src="{{ asset('img/logo/COACHTECHヘッダーロゴ.png') }}" alt="ヘッダーロゴ">
            </a>

            <form class="header-search" action="/" method="get">
                @if(request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif
                    <input class="search-input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
            </form>

            <div class="header-action">
                @auth
                    <form action="/logout" method="post">
                        @csrf
                        <button class="header-action-auth">ログアウト</button>
                    </form>
                @endauth

                @guest
                    <a class="header-action-auth" href="/login">ログイン</a>
                @endguest

                <a class="header-action-profile" href="/mypage">マイページ</a>
                <a class="header-action-create" href="/sell">出品</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    
</body>
</html>