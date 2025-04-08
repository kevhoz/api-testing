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

echo json_encode(["message" => "Produk berhasil ditambahkan"]);
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

echo json_encode(["message" => "Product deleted"]);
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

echo json_encode(["message" => "Product updated"]);
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
  Simple Case of API Testing
</h2>

- Create 2 PHP file; "User.php" in src folder and "UserTest.php" in tests folder
- This is source code for User.php:

![image](https://github.com/user-attachments/assets/25f526df-4b9d-47e1-baa0-2eb3a9c5c18e)

- This is source code for UserTest.php:

![image](https://github.com/user-attachments/assets/2744d3f9-3aab-43e6-a99f-964e9ccf8319)


- Lets try test it: (To test all script inside folder tests)
```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```
- Lets try test it: (To test spesific script inside folder tests)
```
./vendor/bin/phpunit tests/UserTest.php
```
- Simple Case 1, Done

</div>

<div id="unit-testing-2">
<h2>
  Simple case 2: Calculator Testing
</h2>

- Create 2 PHP file; "Calculator.php" in src folder and "CalculatorTest.php" in tests folder
- This is source code for Calculator.php:

![image](https://github.com/user-attachments/assets/4f775ece-ba4d-4901-970c-7b48b940ca2e)

- This is source code for CalculatorTest.php:

![image](https://github.com/user-attachments/assets/6412b3c7-a95f-4179-8249-5af983f9890e)

- Lets try test it: (To test all script inside folder tests)
```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```
- Lets try test it: (To test spesific script inside folder tests)
```
./vendor/bin/phpunit tests/CalculatorTest.php
```
- Simple Case 2, Done

</div>

<div id="unit-testing-3">
<h2>
  Simple case 3: Student Testing
</h2>

- Create 3 PHP file; "Student.php" and "Course.php" in src folder and "StudentTest.php" in tests folder
- This is source code for Student.php:

![image](https://github.com/user-attachments/assets/70a56cc0-5474-4065-aacd-237d1af8df85)

- This is source code for Course.php:

![image](https://github.com/user-attachments/assets/2d13017e-6803-4e90-9126-727234a75417)

- This is source code for StudentTest.php:

![image](https://github.com/user-attachments/assets/311e9cce-1758-4068-9540-7926c90e4e99)

- Lets try test it: (To test all script inside folder tests)
```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```
- Lets try test it: (To test spesific script inside folder tests)
```
./vendor/bin/phpunit tests/StudentTest.php
```
- Simple Case 3, Done

</div>
