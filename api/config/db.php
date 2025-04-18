<?php
require_once __DIR__ . '/env.php';
loadEnv(); // Memuat file .env ke $_ENV

$host = $_ENV['DB_HOST'] ?? 'localhost:3306';
$db   = $_ENV['DB_NAME'] ?? 'api-testing';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     http_response_code(500);
     echo json_encode(["message" => "Database connection error", "error" => $e->getMessage()]);
     exit;
}