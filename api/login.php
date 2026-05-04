<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Method tidak didukung"
    ]);
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode([
        "status" => "error",
        "message" => "Email dan password wajib diisi"
    ]);
    exit;
}

$email = mysqli_real_escape_string($conn, $email);

$query = mysqli_query($conn, "
    SELECT * FROM users
    WHERE email='$email'
    LIMIT 1
");

if (mysqli_num_rows($query) == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User tidak ditemukan"
    ]);
    exit;
}

$user = mysqli_fetch_assoc($query);

if (!password_verify($password, $user['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Password salah"
    ]);
    exit;
}

/* generate token */
$token = bin2hex(random_bytes(32));

mysqli_query($conn, "
    UPDATE users
    SET token='$token'
    WHERE id='".$user['id']."'
");

echo json_encode([
    "status" => "success",
    "message" => "Login berhasil",
    "token" => $token,
    "data" => [
        "id" => $user['id'],
        "nama" => $user['nama'],
        "email" => $user['email'],
        "role" => $user['role']
    ]
]);
?>