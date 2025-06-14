<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenerateReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function generate_report_downloads_pdf_with_correct_view_and_filename()
    {
        // 1. Setup: buat data Mahasiswa dummy
        Mahasiswa::factory()->count(3)->create();

        // 2. Mock Pdf facade
        Pdf::shouldReceive('loadView')
            ->once()
            ->with('reports.mahasiswa', \Mockery::on(function ($data) {
                // Pastikan key 'mahasiswa' ada dan berisi koleksi
                return isset($data['mahasiswa'])
                    && $data['mahasiswa']->count() === 3;
            }))
            ->andReturnSelf();

        Pdf::shouldReceive('download')
            ->once()
            ->with('laporan_mahasiswa.pdf')
            ->andReturn(response('PDF_CONTENT', 200, [
                'Content-Type' => 'application/pdf',
            ]));

        // 3. Buat user dan hit endpoint sebagai user terautentikasi
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard.generateReport'));

        // 4. Assertions
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertEquals('PDF_CONTENT', $response->getContent());
    }
}
