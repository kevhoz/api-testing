<?php
function checkToken($headers) {
    $valid_token = '1234567890ABCDEF'; // Simpan di .env kalau mau aman
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["message" => "Missing token"]);
        exit;
    }

    $token = trim(str_replace("Bearer", "", $headers['Authorization']));
    if ($token !== $valid_token) {
        http_response_code(403);
        echo json_encode(["message" => "Invalid token"]);
        exit;
    }
}
