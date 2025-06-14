<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_with_valid_credentials_redirects_to_dashboard_and_authenticates()
    {
        // Buat user dummy dengan password yang diketahui
        $password = 'secret123';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        // Kirim request POST ke route login.action
        $response = $this->post(route('login.action'), [
            'email'    => $user->email,
            'password' => $password,
        ]);

        // Pastikan redirect ke dashboard
        $response->assertRedirect(route('dashboard'));

        // Pastikan user sudah diautentikasi
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_with_invalid_credentials_shows_error()
    {
        // Kirim credentials salah
        $response = $this->post(route('login.action'), [
            'email'    => 'doesnotexist@example.com',
            'password' => 'wrongpassword',
        ]);

        // Pastikan ada error pada session (validasi gagal)
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function login_requires_email_and_password()
    {
        // Kirim tanpa data apapun
        $response = $this->from(route('login'))
                         ->post(route('login.action'), []);

        // Pastikan kembali ke halaman login dengan error validasi
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email', 'password']);
    }
}
