<?php
// movies.php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch movies
$moviesStmt = $conn->query("SELECT * FROM Movies");
$movies = $moviesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user bookings
$bookingsStmt = $conn->prepare("SELECT b.showtime_id, b.seats, s.movie_id, s.show_date, s.show_time FROM Bookings b JOIN Showtimes s ON b.showtime_id = s.id WHERE b.user_id = :user_id");
$bookingsStmt->bindParam(':user_id', $user_id);
$bookingsStmt->execute();
$userBookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);

$bookingMap = [];
foreach ($userBookings as $booking) {
    $bookingMap[$booking['movie_id']][] = $booking;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="movies-container">
        <h2>Available Movies</h2>
        <ul class="movie-list">
            <?php foreach ($movies as $movie): ?>
                <li class="movie-item">
                    <img src="<?php echo $movie['poster_url']; ?>" alt="<?php echo $movie['title']; ?>">
                    <h3><?php echo $movie['title']; ?></h3>
                    <p><?php echo $movie['genre']; ?> | <?php echo $movie['duration']; ?> mins</p>
                    <p><?php echo $movie['description']; ?></p>
                    <?php if (isset($bookingMap[$movie['id']])): ?>
                        <div class="booked-status">
                            <p><strong>Booked Showtimes:</strong></p>
                            <?php foreach ($bookingMap[$movie['id']] as $booking): ?>
                                <p><?php echo $booking['show_date'] . ' - ' . $booking['show_time']; ?> (Seats: <?php echo implode(', ', json_decode($booking['seats'])); ?>)</p>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <a href="booking.php?movie_id=<?php echo $movie['id']; ?>" class="book-button">Book Now</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
