<?php
$conn = mysqli_connect("localhost", "root", "", "kegiatan_lab");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>