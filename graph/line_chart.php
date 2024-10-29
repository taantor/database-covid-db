<script>
let myChart;

function displayChart(dates, cases, deaths) {
    const ctx = document.getElementById('myChart').getContext('2d');

    if (myChart) {
        myChart.destroy(); // Destroy the previous chart instance if it exists
    }

    myChart = new Chart(ctx, {
        type: 'line', // Set the chart type to 'line'
        data: {
            labels: dates, // Use the dates from the response as labels
            datasets: [{
                    label: 'Cases',
                    data: cases, // Data for cases
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false, // Do not fill the area under the line
                    tension: 0.1, // Set the tension for smooth curves
                },
                {
                    label: 'Deaths',
                    data: deaths, // Data for deaths
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false, // Do not fill the area under the line
                    tension: 0.1, // Set the tension for smooth curves
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of People'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            },
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
                legend: {
                    display: true,
                }
            },
            interaction: {
                mode: 'index',
                intersect: false
            }
        }
    });
}
</script>