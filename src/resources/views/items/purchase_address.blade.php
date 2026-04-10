@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/purchase_address.css') }}">
@endsection

@section('content')
<h1 class="page-title">住所の変更</h1>
<div class="content">
    <form action="{{ route('address.update', ['item_id' => $item->id]) }}" method="POST">
        @csrf

        <div class="form-group">
            <label>郵便番号</label>
            <input type="text" name="postal_code">
        </div>

        <div class="form-group">
            <label>住所</label>
            <input type="text" name="address">
        </div>

        <div class="form-group">
            <label>建物名</label>
            <input type="text" name="building">
        </div>

        <button class="btn" type="submit">更新する</button>
    </form>
</div>

@endsection