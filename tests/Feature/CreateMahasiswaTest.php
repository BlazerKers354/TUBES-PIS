<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CreateMahasiswaTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /** @test */
    public function it_creates_mahasiswa_with_valid_data()
    {
        $payload = [
            'NIM'        => '202310001',
            'Nama'       => 'Budi Santoso',
            'Alamat'     => 'Jl. Merdeka No.1',
            'Nama_Ayah'  => 'Slamet Santoso',
            'Nama_Ibu'   => 'Siti Aminah',
        ];

        $response = $this->post(route('dataMahasiswa.store'), $payload);

        $response->assertRedirect(route('dataMahasiswa'));
        $this->assertDatabaseHas('mahasiswa', [
            'NIM'  => '202310001',
            'Nama' => 'Budi Santoso',
        ]);
    }

    /** @test */
    public function it_fails_validation_when_required_fields_missing()
    {
        $payload = [];
        $response = $this->post(route('dataMahasiswa.store'), $payload);

        $response->assertSessionHasErrors([
            'NIM', 'Nama', 'Alamat', 'Nama_Ayah', 'Nama_Ibu',
        ]);
    }
}
