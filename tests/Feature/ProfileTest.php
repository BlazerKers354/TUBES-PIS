<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_are_redirected_from_profile_page()
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_view_profile_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile');
        $response->assertSeeText($user->name);
    }

    /** @test */
    public function authenticated_user_can_update_their_profile()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'phone' => '1234567890',
            'address' => 'Old Address',
        ]);

        $payload = [
            'name'    => 'New Name',
            'phone'   => '0987654321',
            'address' => 'New Address',
        ];

        $response = $this->actingAs($user)->post('/profile', $payload);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('success', 'Profile updated!');

        $this->assertDatabaseHas('users', [
            'id'      => $user->id,
            'name'    => 'New Name',
            'phone'   => '0987654321',
            'address' => 'New Address',
        ]);
    }
}
