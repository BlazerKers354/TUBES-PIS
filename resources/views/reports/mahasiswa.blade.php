<?php
namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function mahasiswa()
    {
        $mahasiswa = Mahasiswa::all();
        $pdf = Pdf::loadView('reports.mahasiswa', compact('mahasiswa'));
        return $pdf->download('laporan_mahasiswa.pdf');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Mahasiswa</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>
    <h2>Laporan Data Mahasiswa</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Nama Ayah</th>
                <th>Nama Ibu</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa as $mhs)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mhs->NIM }}</td>
                <td>{{ $mhs->Nama }}</td>
                <td>{{ $mhs->Nama_Ayah }}</td>
                <td>{{ $mhs->Nama_Ibu }}</td>
                <td>{{ $mhs->Alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
<?php