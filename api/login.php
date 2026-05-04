<?php
include("config.php");

$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

if (!$email || !$password) {
    echo json_encode([
        "status" => "error",
        "message" => "Email dan password wajib diisi"
    ]);
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

if (mysqli_num_rows($query) == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email tidak ditemukan"
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

echo json_encode([
    "status" => "success",
    "message" => "Login berhasil",
    "data" => [
        "id" => $user["id"],
        "nama" => $user["nama"],
        "email" => $user["email"],
        "role" => $user["role"]
    ]
]);
?>