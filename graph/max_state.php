<?php
include 'server.php';

try {
    $query = "SELECT t1.date, t1.state, t1.fips, t1.cases, t1.deaths
    FROM us_state_miss t1
    JOIN (
    SELECT state, MAX(date) AS max_date
    FROM us_state_miss
    GROUP BY state
) t2 ON t1.state = t2.state AND t1.date = t2.max_date;";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $statesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Create a mapping of state names to data
    $stateDataMap = [];
    foreach ($statesData as $state) {
        $stateDataMap[$state['state']] = [
            'fips' => $state['fips'],
            'cases' => $state['cases'],
            'deaths' => $state['deaths'],
        ];
    }

    echo json_encode($stateDataMap);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>