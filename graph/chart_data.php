<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include 'server_test.php';

// Get state parameter
if (!isset($_GET['state'])) {
    http_response_code(400);
    echo json_encode(['error' => 'State parameter is missing.']);
    exit();
}

$state = $_GET['state'];

// Query to fetch cases and deaths by date for the selected state
$query = $pdo->prepare("SELECT date, cases, deaths FROM us_state_miss WHERE state = :state ORDER BY date");
$query->execute(['state' => $state]);

$dates = [];
$cases = [];
$deaths = [];

// Fetch data
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $dates[] = $row['date'];
    $cases[] = $row['cases'];
    $deaths[] = $row['deaths'];
}

// Prepare response
$response = [
    'dates' => $dates,
    'cases' => $cases,
    'deaths' => $deaths
];

// Set header for JSON response
header('Content-Type: application/json');

// Return JSON response
echo json_encode($response);
?>
