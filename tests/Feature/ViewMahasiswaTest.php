<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewMahasiswaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user dan log in
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test */
    public function index_displays_mahasiswa_list()
    {
        Mahasiswa::create([
            'NIM'       => '2021001',
            'Nama'      => 'Budi Santoso',
            'Alamat'    => 'Jl. Merdeka No.1',
            'Nama_Ayah' => 'Slamet Santoso',
            'Nama_Ibu'  => 'Sri Rahayu',
        ]);
        Mahasiswa::create([
            'NIM'       => '2021002',
            'Nama'      => 'Ani Wijaya',
            'Alamat'    => 'Jl. Sudirman No.2',
            'Nama_Ayah' => 'Joko Wijaya',
            'Nama_Ibu'  => 'Ratna Wijaya',
        ]);

        $response = $this->get(route('dataMahasiswa'));

        $response->assertStatus(200);
        $response->assertViewIs('dataMahasiswa.index');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Ani Wijaya');
    }

    /** @test */
    public function show_displays_specific_mahasiswa_detail()
    {
        $m = Mahasiswa::create([
            'NIM'       => '2021003',
            'Nama'      => 'Citra Lestari',
            'Alamat'    => 'Jl. Thamrin No.3',
            'Nama_Ayah' => 'Adi Lestari',
            'Nama_Ibu'  => 'Maya Lestari',
        ]);

        $response = $this->get(route('dataMahasiswa.show', ['id' => $m->id]));

        $response->assertStatus(200);
        $response->assertViewIs('dataMahasiswa.show');
        $response->assertSee('Citra Lestari');
        $response->assertSee('Jl. Thamrin No.3');
    }
}
