@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')

<h1 class="sell-title">商品の出品</h1>

<div class="sell-container">
    <form action="/sell" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 商品画像 -->
        <label class="form-label">商品画像</label>
        <div class="image-upload">
            <label class="upload-btn" for="image">画像を選択する</label>
            <input class="file-input" type="file" name="item_image" id="image">
        </div>

        <!-- 商品の詳細 -->
        <div class="sell-section">
            <h2 class="section-title">商品の詳細</h2>

            <div class="form-group">
                <label class="form-label">カテゴリー</label>

                <div class="category-list">
                    @foreach ($categories as $category)
                        <label>
                            <input class="category-checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}">
                            <span class="category-tag">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group select-box">
                <label class="form-label">商品の状態</label>
                <select class="form-select" name="condition">
                    <option value="">選択してください</option>
                    <option value="良好">良好</option>
                    <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                    <option value="状態が悪い">状態が悪い</option>
                </select>
            </div>
        </div>

        <!-- 商品名と説明 -->
        <div class="sell-section">
            <h2 class="section-title">商品名と説明</h2>
            <div class="form-group">
                <label class="form-label">商品名</label>
                <input class="form-input" type="text" name="item_name">
            </div>

            <div class="form-group">
                <label class="form-label">ブランド名</label>
                <input class="form-input" type="text" name="brand_name">
            </div>

            <div class="form-group">
                <label class="form-label">商品の説明</label>
                <textarea class="form-textarea" name="text"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">販売価格</label>
                <div class="price-wrapper">
                    <input class="form-input" type="number" name="price">
                    <span class="yen">￥</span>
                </div>
            </div>
        </div>

        <!-- 出品ボタン -->
        <div class="sell-submit">
            <button class="sell-button" type="submit">出品する</button>
        </div>
    </form>
</div>

@endsection