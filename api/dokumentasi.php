<?php
include("config.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":

        $query = mysqli_query($conn, "
            SELECT
                dokumentasi.id,
                kegiatan.nama_kegiatan,
                users.nama AS uploader,
                dokumentasi.jenis_dokumen,
                dokumentasi.nama_file,
                dokumentasi.file_path,
                dokumentasi.uploaded_at,
                dokumentasi.status
            FROM dokumentasi
            JOIN kegiatan ON dokumentasi.kegiatan_id = kegiatan.id
            JOIN users ON dokumentasi.user_id = users.id
            ORDER BY dokumentasi.id DESC
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

        $kegiatan_id = $_POST['kegiatan_id'] ?? null;
        $user_id = $_POST['user_id'] ?? null;
        $jenis_dokumen = $_POST['jenis_dokumen'] ?? null;

        if(!$kegiatan_id || !$user_id || !$jenis_dokumen || !isset($_FILES['file'])){
            echo json_encode([
                "status" => "error",
                "message" => "Field tidak lengkap"
            ]);
            exit;
        }

        $file = $_FILES['file'];

        $nama_file = time() . "_" . basename($file['name']);
        $target = "../uploads/" . $nama_file;
        $file_path = "uploads/" . $nama_file;

        if(move_uploaded_file($file['tmp_name'], $target)){

            $insert = mysqli_query($conn, "
                INSERT INTO dokumentasi
                (
                    kegiatan_id,
                    user_id,
                    jenis_dokumen,
                    nama_file,
                    file_path,
                    status
                )
                VALUES
                (
                    '$kegiatan_id',
                    '$user_id',
                    '$jenis_dokumen',
                    '".$file['name']."',
                    '$file_path',
                    'aktif'
                )
            ");

            echo json_encode([
                "status" => $insert ? "success" : "error",
                "message" => $insert ? "Upload berhasil" : "Gagal simpan database"
            ]);

        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Upload file gagal"
            ]);
        }

        break;


    case "DELETE":

        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'] ?? null;

        if(!$id){
            echo json_encode([
                "status" => "error",
                "message" => "ID wajib diisi"
            ]);
            exit;
        }

        $cek = mysqli_query($conn, "
            SELECT file_path FROM dokumentasi
            WHERE id='$id'
        ");

        $data = mysqli_fetch_assoc($cek);

        if($data){
            $fullPath = "../" . $data['file_path'];

            if(file_exists($fullPath)){
                unlink($fullPath);
            }

            mysqli_query($conn, "
                DELETE FROM dokumentasi
                WHERE id='$id'
            ");

            echo json_encode([
                "status" => "success",
                "message" => "Dokumentasi berhasil dihapus"
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