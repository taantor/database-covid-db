<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    #countrySelect {
        width: 15%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        margin-bottom: 20px;
    }

    .graph-contain {
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'server.php'; ?>
    <div class="container">
        <h1>Dashboard</h1>
        <p>สถิติการใส่แมสจากผลการประเมิน FIPS</p>
        <!-- Dropdown for country selection -->
        <div class="graph-contain">
            <?php
            $sql = "SELECT DISTINCT countyfp FROM mask_use_by_country ORDER BY countyfp ASC";
            $result = $conn->query($sql);

            if ($result->rowCount() > 0) {
                echo '<select id="countrySelect" onchange="updateChart()" class="form-control">';
                echo '<option value="">All FIPS</option>';
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($row['countyfp']) . '">FIPS ' . htmlspecialchars($row['countyfp']) . '</option>';
                }
                echo '</select>';
            } else {
                echo 'No data available';
            }
            ?>
            <canvas id="maskUseChart" width="100" height="50"></canvas>

            <script>
            let chart;

            async function fetchMaskData(country = "") {
                let url = 'show_graph.php';
                if (country) {
                    url += `?country=${country}`;
                }

                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                console.log(data);
                return data;
            }

            async function plotChart(country = "") {
                const maskData = await fetchMaskData(country);

                if (maskData.length === 0) {
                    alert("No data available for the selected country.");
                    return;
                }

                const ctx = document.getElementById('maskUseChart').getContext('2d');

                if (chart) {
                    chart.destroy();
                }

                const labels = country ? [maskData[0].countyfp] : maskData.map(item => item.countyfp);
                const neverData = maskData.map(item => parseFloat(item.never));
                const rarelyData = maskData.map(item => parseFloat(item.rarely));
                const sometimesData = maskData.map(item => parseFloat(item.sometimes));
                const frequentlyData = maskData.map(item => parseFloat(item.frequently));
                const alwaysData = maskData.map(item => parseFloat(item.always));

                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Never',
                                data: neverData,
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 0)',
                            },
                            {
                                label: 'Rarely',
                                data: rarelyData,
                                backgroundColor: 'rgba(230, 100, 255, 0.5)',
                                borderColor: 'rgba(230, 100, 255, 0)',
                            },
                            {
                                label: 'Sometimes',
                                data: sometimesData,
                                backgroundColor: 'rgba(112, 112, 255, 0.5)',
                                borderColor: 'rgba(112, 112, 255, 0)',
                            },
                            {
                                label: 'Frequently',
                                data: frequentlyData,
                                backgroundColor: 'rgba(246, 255, 100, 0.5)',
                                borderColor: 'rgba(246, 255, 100, 0)',
                            },
                            {
                                label: 'Always',
                                data: alwaysData,
                                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        barPercentage: 1.0,
                        categoryPercentage: 1.0
                    }
                });
            }

            function updateChart() {
                const selectedCountry = document.getElementById('countrySelect').value;
                plotChart(selectedCountry);
            }

            document.addEventListener("DOMContentLoaded", async function() {
                const countrySelect = document.getElementById('countrySelect');
                if (countrySelect.options.length > 1) {
                    const firstCountry = countrySelect.options[1].value;
                    countrySelect.value = firstCountry;
                    plotChart(firstCountry);
                } else {
                    plotChart("");
                }
            });
            </script>
        </div>
    </div>
</body>

</html>