<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Show errors for debugging
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt'); // Log errors to this file

header('Content-Type: application/json');

// Include the connection script
include 'server.php'; // Make sure this path is correct

if (isset($_GET['state'])) {
    $state = $_GET['state'];

    try {
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT date, cases, deaths FROM us_state_miss WHERE state = :state ORDER BY date");
        $stmt->bindParam(':state', $state, PDO::PARAM_STR);
        $stmt->execute();
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if data is retrieved
        if ($data) {
            $dates = array_column($data, 'date');
            $cases = array_column($data, 'cases');
            $deaths = array_column($data, 'deaths');

            echo json_encode([
                'dates' => $dates,
                'cases' => $cases,
                'deaths' => $deaths
            ]);
        } else {
            echo json_encode(['error' => 'No data found for the specified state.']);
        }
    } catch (PDOException $e) {
        // Return any errors encountered during the execution
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'State parameter is missing']);
}
?>
