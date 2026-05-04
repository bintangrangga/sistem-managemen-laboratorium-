<?php
include("config.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":

        $query = mysqli_query($conn, "
            SELECT
                absensi.id,
                absensi.user_id,
                users.nama AS nama_user,
                absensi.kegiatan_id,
                kegiatan.nama_kegiatan,
                absensi.waktu,
                absensi.status_kehadiran
            FROM absensi
            JOIN users ON absensi.user_id = users.id
            JOIN kegiatan ON absensi.kegiatan_id = kegiatan.id
            ORDER BY absensi.id DESC
        ");

        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
        break;


    case "POST":

        $user_id = $_POST['user_id'] ?? null;
        $kegiatan_id = $_POST['kegiatan_id'] ?? null;
        $status_kehadiran = $_POST['status_kehadiran'] ?? 'hadir';

        if (!$user_id || !$kegiatan_id) {
            echo json_encode([
                "status" => "error",
                "message" => "user_id dan kegiatan_id wajib diisi"
            ]);
            exit;
        }

        $query = mysqli_query($conn, "
            INSERT INTO absensi
            (
                user_id,
                kegiatan_id,
                waktu,
                status_kehadiran
            )
            VALUES
            (
                '$user_id',
                '$kegiatan_id',
                NOW(),
                '$status_kehadiran'
            )
        ");

        echo json_encode([
            "status" => $query ? "success" : "error",
            "message" => $query ? "Absensi berhasil ditambahkan" : "Gagal menambahkan absensi"
        ]);
        break;


    default:
        echo json_encode([
            "status" => "error",
            "message" => "Method tidak didukung"
        ]);
}
?>