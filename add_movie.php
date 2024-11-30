<?php

session_start();
require 'db_config.php';
require 'FPDF-master/fpdf.php'; // Include the FPDF library

// Check if user is an admin
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch user info to check if the user is an admin
$user_id = $_SESSION['user_id'];
$userStmt = $conn->prepare("SELECT role FROM Users WHERE id = :user_id");
$userStmt->bindParam(':user_id', $user_id);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin') {
    header("Location: movies.php");
    exit();
}

// Handle add movie form submission
if (isset($_POST['add_movie'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $poster_url = $_POST['poster_url'];
    $trailer_url = $_POST['trailer_url'];

    $stmt = $conn->prepare("INSERT INTO Movies (title, description, genre, duration, poster_url, trailer_url) 
                            VALUES (:title, :description, :genre, :duration, :poster_url, :trailer_url)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':genre', $genre);
    $stmt->bindParam(':duration', $duration);
    $stmt->bindParam(':poster_url', $poster_url);
    $stmt->bindParam(':trailer_url', $trailer_url);

    if ($stmt->execute()) {
        $movie_id = $conn->lastInsertId();
        $success_message = "Movie added successfully!";
    } else {
        $error_message = "Error adding movie!";
    }
}

// Handle delete movie form submission
if (isset($_POST['delete_movie'])) {
    $movie_title = $_POST['movie_title'];

    $stmt = $conn->prepare("DELETE FROM Movies WHERE title = :title");
    $stmt->bindParam(':title', $movie_title);

    if ($stmt->execute()) {
        $delete_success_message = "Movie deleted successfully!";
    } else {
        $delete_error_message = "Error deleting movie!";
    }
}

// Generate PDF Report
if (isset($_POST['generate_report'])) {
    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Movies Booking Statistics', 0, 1, 'C');
            $this->Ln(10);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    // Fetch statistics
    // Most booked movie
    $mostBookedStmt = $conn->query("SELECT m.title, COUNT(b.id) AS bookings_count 
                                    FROM Movies m 
                                    JOIN Showtimes s ON m.id = s.movie_id 
                                    JOIN Bookings b ON s.id = b.showtime_id 
                                    GROUP BY m.id 
                                    ORDER BY bookings_count DESC 
                                    LIMIT 1");
    $mostBooked = $mostBookedStmt->fetch(PDO::FETCH_ASSOC);

    // Least booked movie
    $leastBookedStmt = $conn->query("SELECT m.title, COUNT(b.id) AS bookings_count 
                                    FROM Movies m 
                                    JOIN Showtimes s ON m.id = s.movie_id 
                                    LEFT JOIN Bookings b ON s.id = b.showtime_id 
                                    GROUP BY m.id 
                                    ORDER BY bookings_count ASC 
                                    LIMIT 1");
    $leastBooked = $leastBookedStmt->fetch(PDO::FETCH_ASSOC);

    // Total seats booked
    $totalSeatsStmt = $conn->query("SELECT SUM(JSON_LENGTH(b.seats)) AS total_seats 
                                    FROM Bookings b");
    $totalSeats = $totalSeatsStmt->fetch(PDO::FETCH_ASSOC)['total_seats'];

    // Average bookings per movie
    $avgBookingsStmt = $conn->query("SELECT AVG(bookings_count) AS avg_bookings 
                                     FROM (
                                         SELECT COUNT(b.id) AS bookings_count 
                                         FROM Movies m 
                                         JOIN Showtimes s ON m.id = s.movie_id 
                                         JOIN Bookings b ON s.id = b.showtime_id 
                                         GROUP BY m.id
                                     ) subquery");
    $avgBookings = $avgBookingsStmt->fetch(PDO::FETCH_ASSOC)['avg_bookings'];

    // Total movies available
    $totalMoviesStmt = $conn->query("SELECT COUNT(*) AS total_movies FROM Movies");
    $totalMovies = $totalMoviesStmt->fetch(PDO::FETCH_ASSOC)['total_movies'];

    // Most popular genre
    $popularGenreStmt = $conn->query("SELECT m.genre, COUNT(b.id) AS bookings_count 
                                      FROM Movies m 
                                      JOIN Showtimes s ON m.id = s.movie_id 
                                      JOIN Bookings b ON s.id = b.showtime_id 
                                      GROUP BY m.genre 
                                      ORDER BY bookings_count DESC 
                                      LIMIT 1");
    $popularGenre = $popularGenreStmt->fetch(PDO::FETCH_ASSOC);

    // Monthly booking trends
    $monthlyBookingsStmt = $conn->query("SELECT DATE_FORMAT(s.show_date, '%Y-%m') AS month, COUNT(b.id) AS bookings_count 
                                         FROM Showtimes s 
                                         JOIN Bookings b ON s.id = b.showtime_id 
                                         GROUP BY month 
                                         ORDER BY month");
    $monthlyBookings = $monthlyBookingsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Create PDF
    $pdf = new PDF();
    $pdf->AddPage();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Most Booked Movie: " . $mostBooked['title'] . " (" . $mostBooked['bookings_count'] . " bookings)", 0, 1);
    $pdf->Cell(0, 10, "Least Booked Movie: " . $leastBooked['title'] . " (" . $leastBooked['bookings_count'] . " bookings)", 0, 1);
    $pdf->Cell(0, 10, "Total Seats Booked: " . $totalSeats, 0, 1);
    $pdf->Cell(0, 10, "Average Bookings per Movie: " . round($avgBookings, 2), 0, 1);
    $pdf->Cell(0, 10, "Total Movies Available: " . $totalMovies, 0, 1);
    $pdf->Cell(0, 10, "Most Popular Genre: " . $popularGenre['genre'] . " (" . $popularGenre['bookings_count'] . " bookings)", 0, 1);

    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Monthly Booking Trends', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    foreach ($monthlyBookings as $monthData) {
        $pdf->Cell(0, 10, $monthData['month'] . ": " . $monthData['bookings_count'] . " bookings", 0, 1);
    }

    $pdf->Output();
    exit();
}

// Fetch all movies
$moviesStmt = $conn->query("SELECT title FROM Movies");
$movies = $moviesStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Panel</h1>

    <!-- Add Movie Form -->
    <form method="POST">
        <h2>Add Movie</h2>
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
        <label for="genre">Genre:</label>
        <input type="text" name="genre" id="genre" required>
        <label for="duration">Duration (minutes):</label>
        <input type="number" name="duration" id="duration" required>
        <label for="poster_url">Poster URL:</label>
        <input type="url" name="poster_url" id="poster_url" required>
        <label for="trailer_url">Trailer URL:</label>
        <input type="url" name="trailer_url" id="trailer_url" required>
        <button type="submit" name="add_movie">Add Movie</button>
    </form>

    <!-- Delete Movie Form -->
    <form method="POST">
        <h2>Delete Movie</h2>
        <label for="movie_title">Select Movie to Delete:</label>
        <select name="movie_title" id="movie_title" required>
            <option value="">Select a Movie</option>
            <?php foreach ($movies as $movie): ?>
                <option value="<?php echo $movie['title']; ?>"><?php echo $movie['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="delete_movie">Delete Movie</button>
    </form>

    <!-- Generate Report Button -->
    <form method="POST">
        <h2>Export Statistics Report</h2>
        <button type="submit" name="generate_report">Generate PDF Report</button>
    </form>
</body>
</html>
