@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')

<!-- プロフィール -->
<div class="container container-sm">
    <div class="mypage-profile">
        <div class="profile-left">
            <div class="profile-icon">
                <img src="{{ asset('storage/' . $profile->profile_image) }}">
            </div>
            <p class="profile-name">
                {{ $user->name }}
            </p>
        </div>

        <div class="profile-right">
            <a href="/mypage/profile" class="profile-edit-btn">
                プロフィールを編集
            </a>
        </div>
    </div>
</div>

<!-- タブ -->
<div class="tabs">
    <div class="container container-md">
        <a class="tab-link {{ $page === 'sell' ? 'active' : '' }}" href="/mypage?page=sell">
            出品した商品
        </a>
        <a class="tab-link {{ $page === 'buy' ? 'active' : '' }}" href="/mypage?page=buy">
            購入した商品
        </a>
    </div>
</div>

<!-- 商品 -->
<div class="container container-lg">
    <div class="mypage-items">
        @foreach ($items as $item)
            <div class="item-card">
                <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="item-card">
                    <img class="item-image" src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
                </a>
                <p class="item-name">{{ $item->item_name }}</p>
                
            </div>
        @endforeach
    </div>
</div>

@endsection