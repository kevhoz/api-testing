<div id="header" align="center">
  <img src="https://media.giphy.com/media/M9gbBd9nbDrOTu1Mqx/giphy.gif" width="100"/>
</div>
<div id="badges" align="center">
  <a href="https://www.linkedin.com/in/kevin-christianto/">
    <img src="https://img.shields.io/badge/LinkedIn-blue?style=for-the-badge&logo=linkedin&logoColor=white" alt="LinkedIn Badge"/>
  </a>
</div>
<div id="github" align="center">
    <img src="https://komarev.com/ghpvc/?username=kevhoz&style=flat-square&color=blue" alt=""/>
</div>
<div id="body-header" align="center">
<h1>
  API Testing
</h1>
</div>
### :hammer_and_wrench: Languages and Tools:
<div>
  <img src="https://github.com/devicons/devicon/blob/master/icons/php/php-original.svg" title="PHP"  alt="PHP" width="40" height="40"/>&nbsp;
</div>

### :woman_technologist: This repository cover:

1. Prepare backend application.
2. Simple postman testing.
3. Simple Case of API Testing.

<div id="prepare-backend-application">
<h2>
  Prepare backend application
</h2>

Lets develop the backend application:

PHP
  - You can install PHP from Xampp (apachefriends.org)
  - We need only PHP for this API Testing

Get Development Ready and Install PHPUnit
- Create folder "api-testing" inside your root folder for web hosting (if xampp, then htdocs)
- Go inside the folder and create folder + file, like this structure:
  
  ![image](https://github.com/user-attachments/assets/cfa7d54e-df42-4403-961a-150c3b71dd4e)

- Open file token.php inside folder auth, this is source code for the file:
```
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
```
- Open file db.php inside folder config, this is source code for the file:
```
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
```
- Open file env.php inside folder config, this is source code for the file:
```
<?php
function loadEnv($path = __DIR__ . '/../.env') {
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}
```
- Open file create.php inside folder products, this is source code for the file:
```
<?php
require_once "../api/auth/token.php";
require_once "../api/config/db.php";

checkToken(getallheaders());

$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'];
$price = $data['price'] ?? null;

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

// Jika valid, lanjut insert/update
$stmt = $pdo->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
$stmt->execute([$name, $price]);

echo json_encode([
    "message" => "Produk berhasil ditambahkan",
    "id" => $pdo->lastInsertId()
  ]);
```
- Open file delete.php inside folder products, this is source code for the file:
```
<?php
require_once "../api/auth/token.php";
require_once "../api/config/db.php";

checkToken(getallheaders());

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

$stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
$stmt->execute([$id]);

echo json_encode(["message" => "Product berhasil dihapus"]);
```
- Open file read.php inside folder products, this is source code for the file:
```
<?php
require_once "../api/auth/token.php";
require_once "../api/config/db.php";

checkToken(getallheaders());

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();

echo json_encode($products);
```
- Open file update.php inside folder products, this is source code for the file:
```
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
```
- Open file .env inside root, this is source code for the file:
```
API_KEY=1234567890ABCDEF
DB_HOST=localhost:3306
DB_NAME=api-testing
DB_USER=root
DB_PASS=
```
- Open file .htaccess inside root, this is source code for the file:
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php [QSA,L]
```
- Open file index.php inside root, this is source code for the file:
```
<?php
// Load environment & CORS
require_once "config/env.php";
loadEnv();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$request_uri = str_replace($script_name, '', $_SERVER['REQUEST_URI']);
$request_uri = trim($request_uri, '/');
$uri = explode('/', $request_uri);

error_log("REQUEST_URI: " . $_SERVER['REQUEST_URI']);
error_log("Final parsed URI: " . print_r($uri, true));

if (!isset($uri[0])) {
    http_response_code(404);
    echo json_encode(["message" => "Invalid endpoint"]);
    exit;
}

$module = $uri[0] ?? null;
$action = $uri[1] ?? null;

$filepath = __DIR__ . "/$module/$action.php";

if (file_exists($filepath)) {
    require $filepath;
} else {
    http_response_code(404);
    echo json_encode(["message" => "Endpoint not found"]);
}
```
- Continue to create database on MySQL, here is the SQL Query
- Create Database "api-testing" and table "products"
```
CREATE DATABASE `api-testing`;

CREATE TABLE `api-testing`.`products` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `price` decimal(10,2) NOT NULL,
    PRIMARY KEY (`id`)
);
```
- Backend application ready, continue to testing the api

</div>

<div id="simple-postman-testing">
<h2>
  Simple postman testing
</h2>

- Create new blank collection in your postman workspace
  ![image](https://github.com/user-attachments/assets/44c6c8b8-bb64-4ca7-a314-5d6bcc8dc193)

- Berikan nama collection anda [NIM] api-testing.
- Setup variable for this collection (setup according your setting):
  ![image](https://github.com/user-attachments/assets/f1cf4607-9c76-4d72-a9e0-dec273dde975)

- Setup authorization for this collection, based on token variable:
  ![image](https://github.com/user-attachments/assets/daf5669f-8706-4599-8e32-6e4fe9d79322)

- 1st test, create request for api create products:
  ![image](https://github.com/user-attachments/assets/c3164bba-3ca4-45b7-a987-066884baed3c)

- Name that request "Create" and test input like this:
  ![image](https://github.com/user-attachments/assets/d0cbbf01-3b2f-454c-8cbf-30825a5e7bcb)

- Name that request "Read" and test input like this:
  ![image](https://github.com/user-attachments/assets/349862f9-ac15-445e-a2f9-e768ad36d1e1)

- Name that request "Update" and test input like this:
  ![image](https://github.com/user-attachments/assets/7b594a9b-b22c-42f9-bd84-b7e324332d9e)

- You can check the "Read" request again to check the data already changed or not.
- Name that request "Delete" and test input like this:
  ![image](https://github.com/user-attachments/assets/50435f61-68e9-441a-8d12-27b4b50af352)

- You can check the "Read" request again to check the data already deleted or not.
- Simple postman testing, Done!

</div>

<div id="unit-testing-1">
<h2>
  Simple Case of API Chained Testing
</h2>

- Create New Folder and New Request like this tree structure:
  ![image](https://github.com/user-attachments/assets/b65539ba-ce96-41a7-93a3-a14cea849aeb)

- Note: you can copy paste and edit + rename from request you already created before.
  
- In request: [Create] New Product, you need to add in body and post-response script:
- Here is for the body:

  ![image](https://github.com/user-attachments/assets/d6706192-fc9f-45a3-a5e3-f0c09cd65930)

- Here is for the post-response script:

  ![image](https://github.com/user-attachments/assets/fd5b8dbf-8ce1-4f27-af8f-b8090a5c1ab2)

- In request: [Read] Check if the product created, you need to add in post-response script:
- Here is for the post-response script:

  ![image](https://github.com/user-attachments/assets/195fc036-06c6-4fd7-8b60-7c360e6bdb1d)

- In request: [Update] Update the product, you need to add in body and post-response script:
- Here is for the body:

  ![image](https://github.com/user-attachments/assets/ddd4099f-5c9c-438c-a3cc-7496967de268)

- Here is for the post-response script:

  ![image](https://github.com/user-attachments/assets/c9b7c304-287d-4c65-a80b-8f89a77acdf7)

- In request: [Read] Check if the product updated, you need to add in post-response script:
- Here is for the post-response script:

  ![image](https://github.com/user-attachments/assets/470e2390-22b2-4997-bf3d-8bf9b57e69f0)

- In request: [Delete] Delete product, you need to add in body and post-response script:
- Here is for the body:

  ![image](https://github.com/user-attachments/assets/0175f4f3-7ae5-4f58-8e15-ce1a5d7d6774)

- Here is for the post-response script:

  ![image](https://github.com/user-attachments/assets/1d3d961d-0602-47c5-a082-8914078dc0b0)

- In request: [Read] Check if the product deleted, you need to add in post-response script:
- Here is for the post-response script:

  ![image](https://github.com/user-attachments/assets/c01b7eb9-a031-4bec-9b6d-bbf20f2d1f89)

<h3>
  Let's test it!
</h3>

- Run the folder collections:

  ![image](https://github.com/user-attachments/assets/46534689-d697-4d39-9b44-6790e31edb42)

- Run the collections:

  ![image](https://github.com/user-attachments/assets/f63dcf19-460d-41c2-8583-2c86773037b8)

- Result:
  ![image](https://github.com/user-attachments/assets/bc195ab5-80f4-4d02-a50b-6f4e8891caf5)

- Simple Case of API Chained Testing, Done

</div>
