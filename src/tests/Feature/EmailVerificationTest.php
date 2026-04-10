<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_ボタンを押下するとメール認証サイトに遷移()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/verify-email');

        $response->assertSee(
            '<a class="verify-link" href="http://localhost:8025"',
            false
        );
    }

    public function test_メール認証完了後プロフィール画面に遷移する()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );
        $response = $this->get($url);

        $response->assertRedirect('/mypage/profile');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
