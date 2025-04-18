<?php
require_once "../api/auth/token.php";
require_once "../api/config/db.php";

checkToken(getallheaders());

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$name = $data['name'];
$price = $data['price']?? null;

// Validasi harga
if (!is_numeric($price)) {
    http_response_code(400);
    echo json_encode(["message" => "Harga harus berupa angka"]);
    exit;
}

if ($price <= 0) {
    http_response_code(400);
    echo json_encode(["message" => "Harga tidak boleh nol atau negatif"]);
    exit;
}

if ($price > 90000000) {
    http_response_code(400);
    echo json_encode(["message" => "Harga tidak boleh lebih dari 90 juta"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE products SET name=?, price=? WHERE id=?");
$stmt->execute([$name, $price, $id]);

echo json_encode(["message" => "Produk berhasil diupdate"]);
