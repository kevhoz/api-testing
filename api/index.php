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

error_log("script_name: " . $script_name);
error_log("request_uri: " . $request_uri);
error_log("SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME']);
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
