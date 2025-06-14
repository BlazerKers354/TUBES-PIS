<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditMahasiswaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function edit_page_displays_existing_mahasiswa_data()
    {
        $this->actingAs(User::factory()->create());

        $mhs = Mahasiswa::factory()->create([
            'NIM'       => '123456',
            'Nama'      => 'Budi',
            'Alamat'    => 'Jakarta',
            'Nama_Ayah' => 'Slamet',
            'Nama_Ibu'  => 'Siti',
        ]);

        $response = $this->get(route('dataMahasiswa.edit', $mhs->id));

        $response->assertStatus(200)
                 ->assertViewIs('dataMahasiswa.edit')
                 ->assertViewHas('mahasiswa', function($viewMhs) use ($mhs) {
                     return $viewMhs->id === $mhs->id
                         && $viewMhs->Nama === 'Budi'
                         && $viewMhs->NIM === '123456';
                 });
    }

    /** @test */
    public function update_succeeds_with_valid_input()
    {
        $this->actingAs(User::factory()->create());

        $mhs = Mahasiswa::factory()->create();

        $response = $this->put(route('dataMahasiswa.update', $mhs->id), [
            'NIM'       => '654321',
            'Nama'      => 'Andi',
            'Alamat'    => 'Bandung',
            'Nama_Ayah' => 'Joko',
            'Nama_Ibu'  => 'Dewi',
        ]);

        $response->assertRedirect(route('dataMahasiswa'))
                 ->assertSessionHas('success', 'Data Mahasiswa Berhasil Diperbarui!');

        $this->assertDatabaseHas('mahasiswa', [
            'id'    => $mhs->id,
            'NIM'   => '654321',
            'Nama' => 'Andi',
        ]);
    }

    /** @test */
    public function update_fails_with_missing_required_fields()
    {
        $this->actingAs(User::factory()->create());

        $mhs = Mahasiswa::factory()->create();

        $response = $this->from(route('dataMahasiswa.edit', $mhs->id))
                         ->put(route('dataMahasiswa.update', $mhs->id), [
                             'NIM'       => '',
                             'Nama'      => '',
                             'Alamat'    => '',
                             'Nama_Ayah' => '',
                             'Nama_Ibu'  => '',
                         ]);

        $response->assertRedirect(route('dataMahasiswa.edit', $mhs->id))
                 ->assertSessionHasErrors(['NIM', 'Nama', 'Alamat', 'Nama_Ayah', 'Nama_Ibu']);

        $this->assertNotEquals('', $mhs->fresh()->Nama);
    }

    /** @test */
    public function update_returns_error_and_redirects_when_mahasiswa_not_found()
    {
        $this->actingAs(User::factory()->create());

        $invalidId = 9999;

        $response = $this->put(route('dataMahasiswa.update', $invalidId), [
            'NIM'       => '000000',
            'Nama'      => 'Testing',
            'Alamat'    => 'Mana saja',
            'Nama_Ayah' => 'Pak Testing',
            'Nama_Ibu'  => 'Bu Testing',
        ]);

        $response->assertStatus(302)
                 ->assertSessionHas('error', 'Data Mahasiswa tidak ditemukan.');
    }
}
