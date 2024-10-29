<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choropleth Map Example</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        #map { width: 100%; height: 630px; margin: 0 auto; }
        body { font-family: Arial, sans-serif; }
        .info {
            padding: 10px 12px;
            font: 18px/20px Arial, sans-serif;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }
        .legend {
            text-align: left;
            line-height: 18px;
            color: #555;
        }
        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div id="map"></div>

    <!-- Modal for Chart -->
    <div class="modal fade" id="chartModal" tabindex="-1" aria-labelledby="chartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chartModalLabel">COVID-19 Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const map = L.map('map').setView([50, -110], 3);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        function getColor(d) {
            return d > 1000 ? '#800026' :
                   d > 500  ? '#BD0026' :
                   d > 200  ? '#E31A1C' :
                   d > 100  ? '#FC4E2A' :
                   d > 50   ? '#FD8D3C' :
                   d > 20   ? '#FEB24C' :
                   d > 10   ? '#FED976' :
                              '#FFEDA0';
        }

        function style(feature) {
            return {
                fillColor: getColor(feature.properties.density),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7
            };
        }

        // Fetch state data from the server
        let stateDataMap = {};

        fetch('max_state.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                stateDataMap = data; // Store state data in the global variable
            })
            .catch(error => {
                console.error('Error fetching state data:', error);
            });

        function onEachFeature(feature, layer) {
            layer.on({
                mouseover: function(e) {
                    layer.setStyle({
                        weight: 5,
                        color: '#666',
                        dashArray: '',
                        fillOpacity: 0.7
                    });

                    const stateProps = stateDataMap[feature.properties.name] || {};
                    info.update({ 
                        name: feature.properties.name,
                        fips: stateProps.fips || 'N/A', 
                        cases: stateProps.cases || 'N/A', 
                        deaths: stateProps.deaths || 'N/A' 
                    });
                },
                mouseout: function(e) {
                    geojson.resetStyle(e.target);
                    info.update(); // Reset info
                },
                click: function(e) {
                    // Get state name from the feature properties
                    const stateName = feature.properties.name;
                    
                    // Fetch data for the selected state
                    fetch(`chart_data.php?state=${encodeURIComponent(stateName)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(graphData => {
                            // Display the chart with the fetched data
                            displayChart(graphData.dates, graphData.cases, graphData.deaths);
                        })
                        .catch(error => {
                            console.error('Error fetching graph data:', error);
                        });

                    // Show the chart modal
                    $('#chartModal').modal('show');
                }
            });
        }

        const geojson = L.geoJson(statesData, {
            style: style,
            onEachFeature: onEachFeature
        }).addTo(map);

        const info = L.control();

        info.onAdd = function (map) {
            this._div = L.DomUtil.create('div', 'info');
            this.update();
            return this._div;
        };

        info.update = function (props) {
            this._div.innerHTML = props ? `<h4>${props.name}</h4>FIPS: ${props.fips}<br />Cases: ${props.cases}<br />Deaths: ${props.deaths}` : 'Hover over a state';
        };

        info.addTo(map);

        let myChart;  // Declare myChart at the top to ensure it's in the correct scope

        function displayChart(dates, cases, deaths) {
            const ctx = document.getElementById('myChart').getContext('2d');
            
            if (myChart) {
                myChart.destroy(); // ถ้ามีกราฟอยู่แล้ว ให้ทำลายกราฟเดิมก่อน
            } 

            myChart = new Chart(ctx, {
                type: 'bar',  // ประเภทของกราฟเป็น bar
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: 'Cases',
                            data: cases,
                            backgroundColor: 'rgba(255, 99, 132, 0.9)',  // สีของกราฟ
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'Deaths',
                            data: deaths,
                            backgroundColor: 'rgba(54, 162, 235, 0.9)', // สีของกราฟ
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
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
                    }
                }
            });
        }

    </script>
</body>
</html>
