@extends('layouts.app')

@section('content')

<h1 class="page-title">ログイン</h1>

<div class="content">
    <form action="/login" method="post">
        @csrf

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email">
            
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password">
            
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        @error('login')
            <div class="error">{{ $message }}</div>
        @enderror

        <button class="btn" type="submit">ログインする</button>
    </form>

    <a class="link" href="/register">会員登録はこちら</a>
</div>

@endsection