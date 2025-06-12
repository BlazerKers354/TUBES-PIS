<?php
namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    /**
     * Display the dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Generate a PDF report.
     *
     * @return \Illuminate\Http\Response
     */

public function generateReport()

{
    // Data yang ingin ditampilkan di PDF, misal data mahasiswa
    $mahasiswa = \App\Models\Mahasiswa::all();

    $pdf = Pdf::loadView('reports.mahasiswa', compact('mahasiswa'));
    return $pdf->download('laporan_mahasiswa.pdf');
}
    /**
     * Display the profile view.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('profile');
    }
}