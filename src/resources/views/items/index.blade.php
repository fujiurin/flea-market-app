@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')

<!-- タブ -->
<div class="tabs">
    <div class="container">
        <!-- おすすめ -->
        <a class="tab-link {{ $tab !== 'mylist' ? 'active' : '' }}"
           href="{{ url('/?' . http_build_query(array_filter(['keyword' => request('keyword')]))) }}">
           おすすめ
        </a>

        <!-- マイリスト -->
        <a class="tab-link {{ $tab === 'mylist' ? 'active' : '' }}"
           href="{{ url('/?' . http_build_query(array_filter(['tab' => 'mylist','keyword' => request('keyword')]))) }}">
           マイリスト
        </a>
    </div>
</div>

<!-- 商品一覧 -->
<div class="container">
    <div class="items-container">
        @foreach ($items as $item)
            <a href="/item/{{ $item->id }}" class="item-card">

                <div class="item-image-wrapper">
                    <img class="item-image"
                         src="{{ asset('storage/' . $item->item_image) }}"
                         alt="{{ $item->item_name }}">

                    @if($item->sold)
                        <span class="sold-label">Sold Out</span>
                    @endif
                </div>

                <p class="item-name">{{ $item->item_name }}</p>
            </a>
        @endforeach
    </div>
</div>

@endsection