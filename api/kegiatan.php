<?php
include("config.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":
        $query = mysqli_query($conn, "SELECT * FROM kegiatan");
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
        $nama = $_POST['nama_kegiatan'] ?? null;
        $tanggal = $_POST['tanggal'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$nama || !$tanggal || !$status) {
            echo json_encode([
                "status" => "error",
                "message" => "Field tidak lengkap"
            ]);
            exit;
        }

        $query = mysqli_query($conn,
            "INSERT INTO kegiatan(nama_kegiatan,tanggal,status)
             VALUES('$nama','$tanggal','$status')"
        );

        echo json_encode([
            "status" => $query ? "success" : "error",
            "message" => $query ? "Data berhasil ditambahkan" : "Gagal menambahkan data"
        ]);
        break;


    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);

        $id = $_PUT['id'] ?? null;
        $nama = $_PUT['nama_kegiatan'] ?? null;
        $tanggal = $_PUT['tanggal'] ?? null;
        $status = $_PUT['status'] ?? null;

        if (!$id || !$nama || !$tanggal || !$status) {
            echo json_encode([
                "status" => "error",
                "message" => "Field tidak lengkap"
            ]);
            exit;
        }

        $query = mysqli_query($conn,
            "UPDATE kegiatan
             SET nama_kegiatan='$nama',
                 tanggal='$tanggal',
                 status='$status'
             WHERE id='$id'"
        );

        echo json_encode([
            "status" => $query ? "success" : "error",
            "message" => $query ? "Data berhasil diupdate" : "Gagal update data"
        ]);
        break;


    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);

        $id = $_DELETE['id'] ?? null;

        if (!$id) {
            echo json_encode([
                "status" => "error",
                "message" => "ID wajib diisi"
            ]);
            exit;
        }

        $query = mysqli_query($conn,
            "DELETE FROM kegiatan WHERE id='$id'"
        );

        echo json_encode([
            "status" => $query ? "success" : "error",
            "message" => $query ? "Data berhasil dihapus" : "Gagal hapus data"
        ]);
        break;


    default:
        echo json_encode([
            "status" => "error",
            "message" => "Method tidak didukung"
        ]);
}
?>