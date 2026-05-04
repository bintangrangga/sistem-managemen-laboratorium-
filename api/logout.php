<?php
include("config.php");

/*
config.php sudah validasi token,
dan sudah menyediakan:
$auth_user
*/

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Method tidak didukung"
    ]);
    exit();
}

$user_id = $auth_user['id'];

$update = mysqli_query($conn, "
    UPDATE users
    SET token = NULL
    WHERE id = '$user_id'
");

if ($update) {
    echo json_encode([
        "status" => "success",
        "message" => "Logout berhasil"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Logout gagal"
    ]);
}
?>