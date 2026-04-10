@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/purchase.css') }}">
@endsection

@section('content')

<form action="{{ route('purchase.store', ['item_id' => $item->id]) }}" method="POST">
    @csrf

    <div class="purchase-container">

        <div class="purchase-left">

            <!-- 商品情報 -->
            <div class="item-info">
                <img src="{{ asset('storage/' . $item->item_image) }}" alt="商品画像">
                <div>
                    <h2>{{ $item->item_name }}</h2>
                    <p>¥ {{ number_format($item->price) }}</p>
                </div>
            </div>

            <hr>

            <!-- 支払い方法 -->
            <div class="payment-method">
                <h3>支払い方法</h3>
                <select name="payment_method" required>
                    <option value="">選択してください</option>
                    <option value="convenience">コンビニ支払い</option>
                    <option value="card">カード支払い</option>
                </select>
            </div>

            <hr>

            <!-- 配送先 -->
            <div class="address">
                <div class="address-header">
                    <h3>配送先</h3>
                    <a href="{{ route('address.edit', ['item_id' => $item->id]) }}">
                        変更する
                    </a>
                </div>

                <p>〒{{ $profile->postal_code }}</p>
                <p>{{ $profile->address }}</p>
                <p>{{ $profile->building }}</p>

                <input type="hidden" name="postal_code" value="{{ $profile->postal_code }}">
                <input type="hidden" name="address" value="{{ $profile->address }}">
                <input type="hidden" name="building" value="{{ $profile->building }}">
            </div>

            <hr>
        </div>

        <div class="purchase-right">
            <div class="summary">
                <div class="summary-row">
                    <span>商品代金</span>
                    <span>¥ {{ number_format($item->price) }}</span>
                </div>

                <div class="summary-row">
                    <span>支払い方法</span>
                    <span id="selected-payment">未選択</span>
                </div>
            </div>

            <button type="submit" class="purchase-button">購入する</button>
        </div>
    </div>
</form>

<script>
    const select = document.querySelector('select[name="payment_method"]');
    const display = document.getElementById('selected-payment');

    if (select && display) {
        select.addEventListener('change', function () {
            if (this.value === 'convenience') {
                display.textContent = 'コンビニ払い';
            } else if (this.value === 'card') {
                display.textContent = 'カード払い';
            } else {
                display.textContent = '未選択';
            }
        });
    }
</script>

@endsection