<?php
include("config.php");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case "GET":
        $query = mysqli_query($conn, "
            SELECT 
                id,
                nama,
                email,
                nim,
                role,
                prodi,
                tempat_lahir,
                tanggal_lahir
            FROM users
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
        $nama = $_POST['nama'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $nim = $_POST['nim'] ?? null;
        $role = $_POST['role'] ?? "user";
        $prodi = $_POST['prodi'] ?? null;
        $tempat_lahir = $_POST['tempat_lahir'] ?? null;
        $tanggal_lahir = $_POST['tanggal_lahir'] ?? null;

        if (!$nama || !$email || !$password) {
            echo json_encode([
                "status" => "error",
                "message" => "Nama, email, dan password wajib diisi"
            ]);
            exit;
        }

        $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

        if (mysqli_num_rows($cek) > 0) {
            echo json_encode([
                "status" => "error",
                "message" => "Email sudah terdaftar"
            ]);
            exit;
        }

        $query = mysqli_query($conn, "
            INSERT INTO users
            (
                nama,
                email,
                password,
                nim,
                role,
                prodi,
                tempat_lahir,
                tanggal_lahir
            )
            VALUES
            (
                '$nama',
                '$email',
                '$password',
                '$nim',
                '$role',
                '$prodi',
                '$tempat_lahir',
                '$tanggal_lahir'
            )
        ");

        echo json_encode([
            "status" => $query ? "success" : "error",
            "message" => $query ? "User berhasil ditambahkan" : "Gagal menambahkan user"
        ]);
        break;


    default:
        echo json_encode([
            "status" => "error",
            "message" => "Method tidak didukung"
        ]);
}
?>