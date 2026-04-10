<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 認証
Route::get('/login', [LoginController::class, 'show'])
    ->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'show']);
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/verify-email', function () {
    return view('auth.verify-email');
})->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])
  ->name('verification.verify');

// マイページ
Route::get('/mypage', [MypageController::class, 'index'])
    ->middleware(['auth','verified']);
Route::get('/mypage/profile', [MypageController::class, 'profile'])
    ->middleware('auth');
Route::post('/mypage/profile', [MypageController::class, 'update'])
    ->middleware(['auth']);

// 商品
Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'show'])
    ->name('items.show');

// 出品
Route::get('/sell', [ItemController::class, 'create'])
    ->middleware(['auth','verified']);
Route::post('/sell', [ItemController::class, 'store'])
    ->middleware(['auth','verified']);

// 購入  
Route::get('/purchase/success', [PurchaseController::class, 'success'])
    ->name('purchase.success');  
Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])
    ->middleware(['auth','verified'])
    ->name('purchase.show');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'purchase'])
    ->middleware(['auth','verified'])
    ->name('purchase.store');
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])
    ->middleware('auth')
    ->name('address.edit');
Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])
    ->middleware('auth')
    ->name('address.update');

// コメント・いいね    
Route::post('/comments', [CommentController::class, 'store']);
Route::post('/likes', [LikeController::class, 'store'])
    ->middleware('auth');
Route::delete('/likes/{item}', [LikeController::class, 'destroy'])
    ->middleware('auth');
