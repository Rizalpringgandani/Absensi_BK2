<?php
include '../../../config/db.php';

// Tampilkan data mengajar
$kelasMengajar = mysqli_query($con, "SELECT * FROM tb_mengajar 
	INNER JOIN tb_guru ON tb_mengajar.id_guru=tb_guru.id_guru
	INNER JOIN tb_master_mapel ON tb_mengajar.id_mapel=tb_master_mapel.id_mapel
	INNER JOIN tb_mkelas ON tb_mengajar.id_mkelas=tb_mkelas.id_mkelas
	INNER JOIN tb_semester ON tb_mengajar.id_semester=tb_semester.id_semester
	INNER JOIN tb_thajaran ON tb_mengajar.id_thajaran=tb_thajaran.id_thajaran
	WHERE tb_mengajar.id_mengajar='$_GET[pelajaran]' AND tb_mengajar.id_mkelas='$_GET[kelas]'  
	AND tb_thajaran.status=1 AND tb_semester.status=1");

$dataKelasMengajar = mysqli_fetch_array($kelasMengajar);
$namaMapel = $dataKelasMengajar['nama_mapel'];

// Tampilkan data walikelas
$walikelas = mysqli_query($con, "SELECT * FROM tb_walikelas 
	INNER JOIN tb_guru ON tb_walikelas.id_guru=tb_guru.id_guru 
	WHERE tb_walikelas.id_mkelas='$_GET[kelas]'");

// Tampilkan data siswa
$qry = mysqli_query($con, "SELECT * FROM tb_siswa WHERE id_mkelas='$_GET[kelas]' ORDER BY nama_siswa ASC");

$tglTerakhir = date('t'); // Mendapatkan jumlah hari dalam bulan ini

// Fungsi untuk mendapatkan tanggal dalam format tertentu
function formatDate($date) {
    return date('d-m-Y', strtotime($date));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Real-Time</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Absensi Real-Time</h2>
    <h3>Nama Guru: <?= $dataKelasMengajar['nama_guru']; ?></h3>
    <h3>Mata Pelajaran: <?= $namaMapel; ?></h3>
    <h3>Wali Kelas: <?= $dataKelasMengajar['nama_guru']; ?></h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <?php for ($i = 1; $i <= $tglTerakhir; $i++) : ?>
                    <th><?= $i; ?></th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($ds = mysqli_fetch_array($qry)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $ds['nama_siswa']; ?></td>
                    <?php for ($i = 1; $i <= $tglTerakhir; $i++) : ?>
                        <td>
                            <?php
                            // Periksa apakah siswa hadir pada tanggal $i
                            $tglAbsen = date('Y-m') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                            $absenQuery = mysqli_query($con, "SELECT * FROM _logabsensi WHERE id_siswa='$ds[id_siswa]' AND tgl_absen='$tglAbsen'");
                            if (mysqli_num_rows($absenQuery) > 0) {
                                echo 'H'; // Jika hadir
                            } else {
                                echo '-'; // Jika tidak hadir
                            }
                            ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
