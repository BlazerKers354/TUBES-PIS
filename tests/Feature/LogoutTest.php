<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_logout()
    {
        // Buat user baru dan login
        $user = User::factory()->create();
        $response = $this->actingAs($user)
                         ->get(route('logout'));

        // 1. Arahkan ulang ke halaman login
        $response->assertRedirect(route('login'));

        // 2. Pastikan session sudah ter-invalidate dan pesan 'success' di-flash
        $response->assertSessionHas('success', 'Berhasil logout');

        // 3. User sekarang dianggap guest
        $this->assertGuest();
    }

    /** @test */
    public function guest_is_redirected_when_trying_to_logout()
    {
        // Jika belum login, akses /logout harus diarahkan ke login
        $response = $this->get(route('logout'));
        $response->assertRedirect(route('login'));
    }
}
