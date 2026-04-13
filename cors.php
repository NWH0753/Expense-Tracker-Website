<?php
// Allow requests from ANY origin (S3, ALB, localhost, etc.)
header("Access-Control-Allow-Origin: *");

// Allow HTTP methods used in your app
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

// Allow headers sent by fetch (important for FormData / JSON)
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Allow cookies/session if needed (optional, safe to include)
header("Access-Control-Allow-Credentials: false");

// Set response type
header("Content-Type: application/json; charset=UTF-8");

// -------------------------------
// Handle preflight (OPTIONS request)
// -------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
