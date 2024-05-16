<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
// Sesuaikan path sesuai lokasi instalasi Composer Anda
use Dompdf\Dompdf;

// Inisialisasi objek Dompdf
$dompdf = new Dompdf();

// Muat HTML dari file atau string
ob_start(); // Mulai output buffer

// Include file atau script PHP yang menghasilkan output HTML Anda
include 'rekap_persemester.php'; // Ganti dengan lokasi file HTML atau PHP yang mengandung HTML dan CSS untuk absensi

$html = ob_get_clean(); // Dapatkan output HTML ke dalam variabel

// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// (Opsional) Atur ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'landscape'); // Atau 'portrait'

// Render HTML sebagai PDF
$dompdf->render();

// Output PDF ke browser untuk di-download
$dompdf->stream("absensi_siswa.pdf", array("Attachment" => 1));
?>
