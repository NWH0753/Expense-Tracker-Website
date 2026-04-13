<?php
/**
 * cors.php
 * Handles Cross-Origin Resource Sharing (CORS) for the Expense Tracker API.
 * This allows the S3 bucket to communicate with the EC2/ALB backend.
 */

// 1. Allow any origin to access this API (Essential for S3-to-ALB communication)
header("Access-Control-Allow-Origin: *");

// 2. Specify which HTTP methods are allowed
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");

// 3. Allow specific headers (Content-Type is required for JSON payloads)
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// 4. Set the content type to JSON for all responses
header('Content-Type: application/json');

// 5. Handle the "Preflight" OPTIONS request automatically sent by browsers
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Return a 200 OK status and exit immediately for preflight checks
    http_response_code(200);
    exit(0);
}
?>
