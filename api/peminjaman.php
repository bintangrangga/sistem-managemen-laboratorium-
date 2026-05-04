<?php
include("config.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":

        $query = mysqli_query($conn, "
            SELECT
                peminjaman.id,
                users.nama AS nama_user,
                kegiatan.nama_kegiatan,
                inventaris.nama_barang,
                peminjaman.jumlah,
                peminjaman.tanggal_pinjam,
                peminjaman.tanggal_kembali,
                peminjaman.status
            FROM peminjaman
            JOIN users ON peminjaman.user_id = users.id
            JOIN kegiatan ON peminjaman.kegiatan_id = kegiatan.id
            JOIN inventaris ON peminjaman.inventaris_id = inventaris.id
            ORDER BY peminjaman.id DESC
        ");

        $data = [];

        while($row = mysqli_fetch_assoc($query)){
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
        $inventaris_id = $_POST['inventaris_id'] ?? null;
        $jumlah = $_POST['jumlah'] ?? null;

        if(!$user_id || !$kegiatan_id || !$inventaris_id || !$jumlah){
            echo json_encode([
                "status" => "error",
                "message" => "Field tidak lengkap"
            ]);
            exit;
        }

        // cek stok
        $cek = mysqli_query($conn, "
            SELECT stok FROM inventaris
            WHERE id='$inventaris_id'
        ");

        $barang = mysqli_fetch_assoc($cek);

        if(!$barang){
            echo json_encode([
                "status" => "error",
                "message" => "Barang tidak ditemukan"
            ]);
            exit;
        }

        if($barang['stok'] < $jumlah){
            echo json_encode([
                "status" => "error",
                "message" => "Stok tidak cukup"
            ]);
            exit;
        }

        // insert peminjaman
        $insert = mysqli_query($conn, "
            INSERT INTO peminjaman
            (
                user_id,
                kegiatan_id,
                inventaris_id,
                jumlah,
                tanggal_pinjam,
                status
            )
            VALUES
            (
                '$user_id',
                '$kegiatan_id',
                '$inventaris_id',
                '$jumlah',
                NOW(),
                'dipinjam'
            )
        ");

        if($insert){
            // kurangi stok
            mysqli_query($conn, "
                UPDATE inventaris
                SET stok = stok - $jumlah
                WHERE id='$inventaris_id'
            ");

            echo json_encode([
                "status" => "success",
                "message" => "Peminjaman berhasil"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Gagal meminjam"
            ]);
        }

        break;


    case "PUT":

        parse_str(file_get_contents("php://input"), $_PUT);

        $id = $_PUT['id'] ?? null;

        if(!$id){
            echo json_encode([
                "status" => "error",
                "message" => "ID wajib diisi"
            ]);
            exit;
        }

        // ambil data pinjaman
        $cek = mysqli_query($conn, "
            SELECT inventaris_id, jumlah
            FROM peminjaman
            WHERE id='$id'
        ");

        $pinjam = mysqli_fetch_assoc($cek);

        if(!$pinjam){
            echo json_encode([
                "status" => "error",
                "message" => "Data tidak ditemukan"
            ]);
            exit;
        }

        // update status
        $update = mysqli_query($conn, "
            UPDATE peminjaman
            SET
                status='dikembalikan',
                tanggal_kembali=NOW()
            WHERE id='$id'
        ");

        if($update){
            // kembalikan stok
            mysqli_query($conn, "
                UPDATE inventaris
                SET stok = stok + ".$pinjam['jumlah']."
                WHERE id='".$pinjam['inventaris_id']."'
            ");

            echo json_encode([
                "status" => "success",
                "message" => "Barang berhasil dikembalikan"
            ]);
        }

        break;


    default:
        echo json_encode([
            "status" => "error",
            "message" => "Method tidak didukung"
        ]);
}
?>