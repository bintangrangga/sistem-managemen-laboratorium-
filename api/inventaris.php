<?php
include("config.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":
        $query = mysqli_query($conn, "SELECT * FROM inventaris ORDER BY id DESC");

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
        $nama_barang = $_POST['nama_barang'] ?? null;
        $stok = $_POST['stok'] ?? null;
        $kondisi = $_POST['kondisi'] ?? 'baik';
        $status = $_POST['status'] ?? 'tersedia';

        if (!$nama_barang || !$stok) {
            echo json_encode([
                "status" => "error",
                "message" => "nama_barang dan stok wajib diisi"
            ]);
            exit;
        }

        $query = mysqli_query($conn, "
            INSERT INTO inventaris
            (nama_barang, stok, kondisi, status)
            VALUES
            ('$nama_barang', '$stok', '$kondisi', '$status')
        ");

        echo json_encode([
            "status" => $query ? "success" : "error",
            "message" => $query ? "Barang berhasil ditambahkan" : "Gagal menambahkan barang"
        ]);
        break;


    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);

        $id = $_PUT['id'] ?? null;
        $nama_barang = $_PUT['nama_barang'] ?? null;
        $stok = $_PUT['stok'] ?? null;
        $kondisi = $_PUT['kondisi'] ?? null;
        $status = $_PUT['status'] ?? null;

        if (!$id) {
            echo json_encode([
                "status" => "error",
                "message" => "ID wajib diisi"
            ]);
            exit;
        }

        $query = mysqli_query($conn, "
            UPDATE inventaris SET
                nama_barang='$nama_barang',
                stok='$stok',
                kondisi='$kondisi',
                status='$status'
            WHERE id='$id'
        ");

        echo json_encode([
            "status" => $query ? "success" : "error",
            "message" => $query ? "Barang berhasil diupdate" : "Gagal update barang"
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

        $query = mysqli_query($conn, "DELETE FROM inventaris WHERE id='$id'");

        echo json_encode([
            "status" => $query ? "success" : "error",
            "message" => $query ? "Barang berhasil dihapus" : "Gagal hapus barang"
        ]);
        break;


    default:
        echo json_encode([
            "status" => "error",
            "message" => "Method tidak didukung"
        ]);
}
?>