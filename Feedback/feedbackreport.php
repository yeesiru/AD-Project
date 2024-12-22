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
        <h1 class="text-center mt-4">Feedback Report</h1>
        <p class="text-center text-muted">Detailed insights into user feedback and ratings</p>
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-md-4">
                <div class="stat-card">
                    <h3>Total Feedback</h3>
                    <p class="display-4"><?php echo array_sum($feedbackCounts); ?></p>
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

        <!-- Bar Chart for Average Ratings -->
        <div class="chart-container">
            <h2 class="text-center">Average Ratings by Service</h2>
            <canvas id="ratingChart"></canvas>
        </div>

        <!-- Pie Chart for Feedback Distribution -->
        <div class="chart-container">
            <h2 class="text-center">Feedback Distribution by Service</h2>
            <canvas id="feedbackChart"></canvas>
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
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
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
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
