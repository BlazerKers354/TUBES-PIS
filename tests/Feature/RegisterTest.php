<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function valid_registration_creates_user_and_redirects_to_login()
    {
        // Arrange: siapkan data input valid
        $formData = [
            'name'                  => 'User Test',
            'email'                 => 'usertest@example.com',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        // Act: kirim POST ke route /register
        $response = $this->post('/register', $formData);

        // Assert: response redirect ke /login sesuai flow aplikasi
        $response->assertRedirect('/login');

        // Assert: user tersimpan di database
        $this->assertDatabaseHas('users', [
            'email' => 'usertest@example.com',
            'name'  => 'User Test',
        ]);

        // Assert: password benar-benar di-hash
        $user = User::where('email', 'usertest@example.com')->first();
        $this->assertTrue(Hash::check('secret123', $user->password));

        // Assert: user belum ter-autentikasi secara otomatis
        $this->assertGuest();
    }

    /** @test */
    public function registration_with_invalid_data_fails_and_shows_errors()
    {
        // Arrange: data input yang pasti invalid (nama kosong, email format salah, password terlalu pendek)
        $formData = [
            'name'                  => '',
            'email'                 => 'bukan-email',
            'password'              => '123',
            'password_confirmation' => '321',
        ];

        // Act: kirim POST
        $response = $this->from('/register')->post('/register', $formData);

        // Assert: kembali ke form register
        $response->assertRedirect('/register');

        // Assert: session error untuk field yang invalid
        $response->assertSessionHasErrors(['name', 'email', 'password']);

        // Assert: tidak ada user baru di database
        $this->assertDatabaseMissing('users', [
            'email' => 'bukan-email',
        ]);

        // Assert: user belum authenticate
        $this->assertGuest();
    }

    /** @test */
    public function registration_fails_if_email_already_exists()
    {
        // Arrange: buat user sudah ada
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $formData = [
            'name'                  => 'Another User',
            'email'                 => 'existing@example.com',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        // Act: kirim POST
        $response = $this->from('/register')->post('/register', $formData);

        // Assert: kembali ke form register
        $response->assertRedirect('/register');

        // Assert: session error untuk email
        $response->assertSessionHasErrors(['email']);

        // Assert: tidak ada user baru dengan email yang sama
        $this->assertEquals(1, User::where('email', 'existing@example.com')->count());

        // Assert: user belum authenticate
        $this->assertGuest();
    }
}
