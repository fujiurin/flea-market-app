<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_名前未入力でバリデーション表示()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_メール未入力でバリデーション表示()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $response->assertSessionHasErrors('email');
    }

    public function test_パスワード未入力でバリデーション表示()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password',
        ]);

    $response->assertSessionHasErrors('password');
    }

    public function test_パスワード7文字以下バリデーション表示()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

    $response->assertSessionHasErrors('password');
    }

    public function test_パスワード不一致でバリデーション表示()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '11345678',
        ]);

    $response->assertSessionHasErrors('password');
    }

    public function test_認証メールが送信される()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('verification.notice'));
    }
}
