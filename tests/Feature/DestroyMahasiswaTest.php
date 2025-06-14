<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyMahasiswaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function destroy_route_requires_authentication()
    {
        // Buat mahasiswa dummy
        $mhs = Mahasiswa::factory()->create();

        // Panggil route tanpa login
        $response = $this->delete(route('dataMahasiswa.destroy', $mhs->id));

        // Expect diarahkan ke login
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_delete_mahasiswa()
    {
        // Buat user dan mahasiswa dummy
        $user = User::factory()->create();
        $mhs  = Mahasiswa::factory()->create();

        // Login sebagai user
        $this->actingAs($user);

        // Pastikan data ada di DB sebelum delete
        $this->assertDatabaseHas('mahasiswa', [
            'id'   => $mhs->id,
            'nama' => $mhs->Nama,
        ]);

        // Panggil route destroy
        $response = $this->delete(route('dataMahasiswa.destroy', $mhs->id));

        // Harus redirect kembali ke index
        $response->assertRedirect(route('dataMahasiswa'));

        // Flash message sukses
        $response->assertSessionHas('success', 'Data Mahasiswa Berhasil Dihapus!');

        // Pastikan data terhapus di DB
        $this->assertDatabaseMissing('mahasiswa', [
            'id' => $mhs->id,
        ]);
    }

    /** @test */
    public function deleting_nonexistent_mahasiswa_returns_404()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Panggil route dengan ID yang tidak ada
        $response = $this->delete(route('dataMahasiswa.destroy', 9999));

        // Harus 404
        $response->assertStatus(404);
    }
}
