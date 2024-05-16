<?php 
// Menampilkan data mengajar
$kelasMengajar = mysqli_query($con, "SELECT * FROM tb_mengajar 
                                    INNER JOIN tb_master_mapel ON tb_mengajar.id_mapel=tb_master_mapel.id_mapel
                                    INNER JOIN tb_mkelas ON tb_mengajar.id_mkelas=tb_mkelas.id_mkelas
                                    INNER JOIN tb_semester ON tb_mengajar.id_semester=tb_semester.id_semester
                                    INNER JOIN tb_thajaran ON tb_mengajar.id_thajaran=tb_thajaran.id_thajaran
                                    WHERE tb_mengajar.id_guru='$data[id_guru]' AND tb_mengajar.id_mengajar='$_GET[pelajaran]' AND tb_thajaran.status=1");

foreach ($kelasMengajar as $d) { 
?>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
    </div>
</div>
<div class="page-inner">
    <div class="page-header">
        <ul class="breadcrumbs" style="font-weight: bold;">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">KELAS (<?= strtoupper($d['nama_kelas']) ?> )</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#"><?= strtoupper($d['mapel']) ?></a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <!-- Form untuk mengelola absensi siswa -->
                    <form action="" method="post">
                        <input type="hidden" name="pelajaran" value="<?= $_GET['pelajaran'] ?>">
                        <table>
                            <?php 
                            // Menampilkan data siswa untuk absensi
                            $tgl_hari_ini = date('Y-m-d');
                            $siswa = mysqli_query($con, "SELECT * FROM _logabsensi 
                                                        INNER JOIN tb_siswa ON _logabsensi.id_siswa=tb_siswa.id_siswa 
                                                        WHERE  _logabsensi.tgl_absen='$tgl_hari_ini' AND _logabsensi.id_mengajar='$_GET[pelajaran]' 
                                                        ORDER BY _logabsensi.id_siswa ASC");

                            foreach ($siswa as $i => $s) { ?>
                                <tr>
                                    <td>
                                        <b class="text-success"><?= $s['nama_siswa']; ?></b>
                                        <?php 
                                        if ($s['ket'] == '') {
                                            echo "<span class='text-danger'>Belum Absen</span>";
                                        } ?>
                                        <input type="hidden" name="id_siswa-<?= $i; ?>" value="<?= $s['id_siswa'] ?>">
                                        <div class="form-check">
                                            <!-- Checkbox untuk mengelola absensi siswa -->
                                            <label class="form-check-label">
                                                <input name="ket-<?= $i; ?>" class="form-check-input" type="checkbox" value="H" <?= ($s['ket']=='H') ? 'checked' : '' ?>>
                                                <span class="form-check-sign">H</span>
                                            </label>
                                            <label class="form-check-label">
                                                <input name="ket-<?= $i; ?>" class="form-check-input" type="checkbox" value="H" <?= ($s['ket']=='S') ? 'checked' : '' ?>>
                                                <span class="form-check-sign">S</span>
                                            </label>
                                            <label class="form-check-label">
                                                <input name="ket-<?= $i; ?>" class="form-check-input" type="checkbox" value="H" <?= ($s['ket']=='I') ? 'checked' : '' ?>>
                                                <span class="form-check-sign">I</span>
                                            </label>
                                            <label class="form-check-label">
                                                <input name="ket-<?= $i; ?>" class="form-check-input" type="checkbox" value="H" <?= ($s['ket']=='T') ? 'checked' : '' ?>>
                                                <span class="form-check-sign">T</span>
                                            </label>
                                            <label class="form-check-label">
                                                <input name="ket-<?= $i; ?>" class="form-check-input" type="checkbox" value="H" <?= ($s['ket']=='A') ? 'checked' : '' ?>>
                                                <span class="form-check-sign">A</span>
                                            </label>
                                            <!-- Lanjutkan dengan checkbox lainnya -->
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                        <center>
                            <button type="submit" name="update" class="btn btn-success">
                                <i class="fa fa-check"></i> Update Absensi
                            </button>
                            <a href="javascript:history.back()" class="btn btn-default">
                                <i class="fas fa-arrow-circle-left"></i> Kembali
                            </a>
                        </center>
                    </form>

                    <?php 
if (isset($_POST['update'])) {
    // Proses pembaruan absensi siswa
    foreach ($_POST as $key => $value) {
        // Pemeriksaan nilai $_POST untuk mendapatkan data absensi siswa yang telah diperbarui
        if (strpos($key, 'ket-') === 0) {
            $id_siswa = $_POST['id_siswa-' . substr($key, 4)]; // Mendapatkan ID siswa dari nama input
            $keterangan = $value; // Mendapatkan keterangan absensi dari nilai input

            // Lakukan pembaruan absensi siswa di database sesuai dengan ID siswa dan keterangan absensi
            $update_absensi = mysqli_query($con, "UPDATE _logabsensi SET ket='$keterangan' WHERE id_siswa='$id_siswa' AND tgl_absen='$tgl_hari_ini' AND id_mengajar='$_GET[pelajaran]'");
            if (!$update_absensi) {
                // Jika terjadi kesalahan dalam pembaruan absensi, tampilkan pesan kesalahan
                echo "<div class='alert alert-danger'>Gagal memperbarui absensi siswa.</div>";
            }
        }
    }

    // Tampilkan pesan sukses setelah pembaruan berhasil
    echo "<div class='alert alert-success'>Absensi siswa berhasil diperbarui.</div>";

    // Redirect pengguna kembali ke halaman sebelumnya setelah pembaruan berhasil
    echo "<script>window.location.href = 'javascript:history.back()';</script>";
}
?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>