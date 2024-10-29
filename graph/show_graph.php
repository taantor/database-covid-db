<?php
// Database connection credentials
include "server.php";

// Get the selected country (COUNTYFP) from the request
$countyfp = isset($_GET['country']) ? $_GET['country'] : '';

// Prepare the SQL query
try {
    if (!empty($countyfp)) {
        // Use ? as placeholder and bind the parameter correctly
        $sql = "SELECT COUNTYFP, NEVER, RARELY, SOMETIMES, FREQUENTLY, ALWAYS FROM mask_use_by_country WHERE COUNTYFP = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$countyfp]);
    } else {
        // If no specific county is selected, return all data
        $sql = "SELECT COUNTYFP, NEVER, RARELY, SOMETIMES, FREQUENTLY, ALWAYS FROM mask_use_by_country";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    // Fetch all data
    $maskData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($maskData);
} catch (PDOException $e) {
    // Return error message as JSON
    echo json_encode(['error' => $e->getMessage()]);
}

// Close the connection
$conn = null;
?>
