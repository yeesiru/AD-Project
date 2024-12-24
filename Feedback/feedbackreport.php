<?php
include("../database/db_conn.php"); // Include the database connection file

// Query to get average rating and feedback count
$query = "SELECT serviceType, AVG(rating) as avgRating, COUNT(*) as totalFeedback FROM feedback GROUP BY serviceType";
$result = $conn->query($query);

// Query for highest and lowest rated service
$highestRatedQuery = "SELECT serviceType, AVG(rating) as avgRating FROM feedback GROUP BY serviceType ORDER BY avgRating DESC LIMIT 1";
$highestRatedResult = $conn->query($highestRatedQuery)->fetch_assoc();

$lowestRatedQuery = "SELECT serviceType, AVG(rating) as avgRating FROM feedback GROUP BY serviceType ORDER BY avgRating ASC LIMIT 1";
$lowestRatedResult = $conn->query($lowestRatedQuery)->fetch_assoc();

// Store data for graphs
$serviceTypes = [];
$averageRatings = [];
$feedbackCounts = [];

while ($row = $result->fetch_assoc()) {
    $serviceTypes[] = $row['serviceType'];
    $averageRatings[] = round($row['avgRating'], 2);
    $feedbackCounts[] = $row['totalFeedback'];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    
    <title>Detailed Feedback Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/feedbackreport.css">


</head>
<body>
    <div class="container">
        <a href="admin_viewfeedback.php" style="text-decoration:none; color: black;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>Back</a>
        <h1 class="text-center mt-4">Feedback Report</h1>
        <p class="text-center text-muted">Detailed insights into user feedback and ratings</p>
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-md-4">
                <div class="stat-card">
                    <h3>Total Feedback</h3>
                    <p class="display-6"><?php echo array_sum($feedbackCounts); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h3>Highest Rated Service</h3>
                    <p class="lead"><?php echo htmlspecialchars($highestRatedResult['serviceType']); ?> (<?php echo round($highestRatedResult['avgRating'], 2); ?>)</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <h3>Lowest Rated Service</h3>
                    <p class="lead"><?php echo htmlspecialchars($lowestRatedResult['serviceType']); ?> (<?php echo round($lowestRatedResult['avgRating'], 2); ?>)</p>
                </div>
            </div>
        </div>

        <!-- Bar Chart and Pie Chart Section -->
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="chart-container">
                    <h2 class="text-center">Average Ratings by Service</h2>
                    <canvas id="ratingChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h2 class="text-center">Feedback Distribution by Service</h2>
                    <canvas id="feedbackChart" style="width: 500px; height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const serviceTypes = <?php echo json_encode($serviceTypes); ?>;
        const averageRatings = <?php echo json_encode($averageRatings); ?>;
        const feedbackCounts = <?php echo json_encode($feedbackCounts); ?>;

        // Bar Chart: Average Ratings
const ratingCtx = document.getElementById('ratingChart').getContext('2d');
new Chart(ratingCtx, {
    type: 'bar',
    data: {
        labels: serviceTypes,
        datasets: [{
            label: 'Average Rating',
            data: averageRatings,
            backgroundColor: '#017b56',
            borderColor: '#1D5748',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 5
            }
        }
    }
});

// Pie Chart: Feedback Distribution
const feedbackCtx = document.getElementById('feedbackChart').getContext('2d');
new Chart(feedbackCtx, {
    type: 'pie',
    data: {
        labels: serviceTypes,
        datasets: [{
            label: 'Feedback Distribution',
            data: feedbackCounts,
            backgroundColor: [
                'rgba(1, 123, 86, 0.7)',
                'rgba(1, 123, 86, 0.88)',
                'rgb(1, 123, 86)'
            ],
            borderColor: [
                'rgb(23, 92, 32)',
                'rgb(23, 92, 32)',
                'rgb(23, 92, 32)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});

    </script>
</body>
</html>
