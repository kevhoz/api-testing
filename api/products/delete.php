<?php
require_once "../api/auth/token.php";
require_once "../api/config/db.php";

checkToken(getallheaders());

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

$stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
$stmt->execute([$id]);

echo json_encode(["message" => "Product berhasil dihapus"]);
