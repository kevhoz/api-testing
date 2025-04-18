<?php
require_once "../api/auth/token.php";
require_once "../api/config/db.php";

checkToken(getallheaders());

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();

echo json_encode($products);
