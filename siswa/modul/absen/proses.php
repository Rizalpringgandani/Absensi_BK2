<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$waktu = date('Y-m-d H:i:s'); // mengambil waktu saat ini

// Menyimpan ke database
$sql = "INSERT INTO tbl_absensi (nama, waktu, latitude, longitude) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nama, $waktu, $latitude, $longitude);

if ($stmt->execute()) {
    $pesan = "Absensi berhasil!";
} else {
    $pesan = "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lokasi Absen</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map {
            height: 200px;
            width: 200px;
        }
    </style>
</head>
<body>

<?php echo "<p>$pesan</p>"; ?>

<div id="map"></div>

<script>
    var map = L.map('map').setView([<?php echo $latitude; ?>, <?php echo $longitude; ?>], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map)
        .bindPopup('Lokasi Absen: <?php echo htmlspecialchars($nama); ?>').openPopup();
</script>

</body>
</html>
