@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="container">
    <div class="item-detail">

        <!-- 商品画像 -->
        <div class="item-image">
            <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
        </div>

        <!-- 商品情報 -->
        <div class="item-info">

            <h1 class="item-name">{{ $item->item_name }}</h1>

            <p class="brand">{{ $item->brand_name }}</p>

            <p class="price">
                ¥{{ number_format($item->price) }}
                <span>（税込）</span>
            </p>

            <div class="item-meta">

                <span>
                    @auth
                        @if($item->likes->where('user_id', auth()->id())->count())
                            <form action="/likes/{{ $item->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn">
                                    <img src="{{ asset('img/ui/ハートロゴ_ピンク.png') }}" alt="いいね">
                                </button>
                            </form>
                        @else
                            <form action="/likes" method="POST">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <button type="submit" class="icon-btn">
                                    <img src="{{ asset('img/ui/ハートロゴ_デフォルト.png') }}" alt="いいね">
                                </button>
                            </form>
                        @endif
                    @else
                        <form action="/likes" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <button type="submit" class="icon-btn">
                                <img src="{{ asset('img/ui/ハートロゴ_デフォルト.png') }}" alt="いいね">
                            </button>
                        </form>
                    @endauth

                    <p>{{ $item->likes->count() }}</p>
                </span>

                <span>
                    <img src="{{ asset('img/ui/ふきだしロゴ.png') }}" alt="コメント">
                    <p>{{ $item->comments->count() }}</p>
                </span>

            </div>

            @if(!$item->sold)
                <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}">
                    <button class="purchase-btn">購入手続きへ</button>
                </a>
            @endif

            <div class="item-description">
                <h2>商品説明</h2>
                <p>{{ $item->text }}</p>
            </div>

            <div class="item-detail-info">
                <h2>商品情報</h2>

                <div class="categories">
                    <p class="info-label">カテゴリー</p>
                    @foreach ($item->categories as $category)
                        <span class="category-tag">{{ $category->name }}</span>
                    @endforeach
                </div>

                <div class="condition">
                    <p class="info-label">商品の状態</p>
                    <p>{{ $item->condition }}</p>
                </div>
            </div>

            <div class="comments">
                <h2>コメント ({{ $item->comments->count() }})</h2>

                @foreach ($item->comments as $comment)
                    <div class="comment-user-info">
                        @if(optional($comment->user->profile)->profile_image)
                            <img class="user-icon"src="{{ asset('storage/' . $comment->user->profile->profile_image) }}">
                        @else
                            <div class="user-icon default-icon"></div>
                        @endif

                        <span class="comment-user">{{ $comment->user->name }}</span>
                    </div>

                    <p class="comment-content">{{ $comment->content }}</p>
                @endforeach
            </div>

                <div class="comment-form">
                    <h3>商品へのコメント</h3>
                    <form action="/comments" method="POST">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <textarea name="content" rows="4"></textarea>

                        @error('content')
                            <p class="error">{{ $message }}</p>
                        @enderror

                        <button type="submit">コメントを送信する</button>
                    </form>
                </div>
        </div>
    </div>
</div>

@endsection