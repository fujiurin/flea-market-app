@extends('layouts.app')

@section('content')

<div class="verification">
    <p class="message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください
    </p>

    <a class="verify-link" href="http://localhost:8025" target="_blank">
        認証はこちらから
    </a>

    <form action="{{ route('verification.send') }}" method="post">
        @csrf

        <button class="resend-btn" type="submit">
            認証メールを再送する
        </button>
    </form>
</div>

@endsection