@extends('layouts.main')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
@endsection

@section('content')
<h1 class="page-title">プロフィール設定</h1>

<form action="/mypage/profile" method="POST" enctype="multipart/form-data">
    @csrf

        <div class="profile-image-area">
            <div class="profile-icon">
                @if($profile && $profile->profile_image)
                    <img src="{{ asset('storage/' . $profile->profile_image) }}" alt="プロフィール画像">
                @else
                    <div class="default-icon"></div>
                @endif
            </div>

            <div class="image-select">
                <label class="image-button" for="profile_image">
                    画像を選択する
                </label>
                <input class="file-input" type="file" name="profile_image" id="profile_image">
            </div>
        </div>

        <div class="form-group">
            <label>
                ユーザー名
            </label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
        </div>

        <div class="form-group">
            <label>
                郵便番号
            </label>
            <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
        </div>

        <div class="form-group">
            <label>
                住所
            </label>
            <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}">
        </div>

        <div class="form-group">
            <label>
                建物名
            </label>
            <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}">
        </div>

        <button class="update-btn" type="submit">
            更新する
        </button>
</form>
@endsection