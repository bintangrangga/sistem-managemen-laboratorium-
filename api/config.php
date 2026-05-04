<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

include("../config/koneksi.php");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/*
|--------------------------------------------------------------------------
| Endpoint yang tidak perlu token
|--------------------------------------------------------------------------
*/
$public_endpoint = [
    "login.php"
];

$current_file = basename($_SERVER['PHP_SELF']);

if (!in_array($current_file, $public_endpoint)) {

    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (!$authHeader) {
        echo json_encode([
            "status" => "error",
            "message" => "Token wajib dikirim"
        ]);
        exit();
    }

    if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        echo json_encode([
            "status" => "error",
            "message" => "Format token salah"
        ]);
        exit();
    }

    $token = $matches[1];

    $token = mysqli_real_escape_string($conn, $token);

    $cek = mysqli_query($conn, "
        SELECT id,nama,email,role
        FROM users
        WHERE token='$token'
        LIMIT 1
    ");

    if (mysqli_num_rows($cek) == 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Unauthorized"
        ]);
        exit();
    }

    /*
    | Simpan user login ke variable global
    */
    $auth_user = mysqli_fetch_assoc($cek);
}
?>