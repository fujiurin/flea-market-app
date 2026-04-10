@extends('layouts.app')

@section('content')

<h1 class="page-title">会員登録</h1>

<div class="content">
    <form action="/register" method="post">
        @csrf

        <div class="form-group">
            <label>ユーザー名</label>
            <input type="text" name="name">
        
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

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

        <div class="form-group">
            <label>確認用パスワード</label>
            <input type="password" name="password_confirmation">
        
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn" type="submit">登録する</button>
    </form>

    <a class="link" href="/login">ログインはこちら</a>
</div>

@endsection