<?php
// movies.php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Fetch movies
$moviesStmt = $conn->query("SELECT * FROM Movies");
$movies = $moviesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user bookings
$user_id = $_SESSION['user_id'];
$bookingsStmt = $conn->prepare("SELECT b.showtime_id, b.seats, s.movie_id, s.show_date, s.show_time 
                                FROM Bookings b 
                                JOIN Showtimes s ON b.showtime_id = s.id 
                                WHERE b.user_id = :user_id");
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
    <h1 id="titre">الأفلام المتوفرة</h1>
    <div class="movies-container">
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
    <br>
    <div class="logout-container">
        <a href="movies.php?logout=true" class="logout-button">Logout</a>
    </div>
    
    <div class="theme-toggle-container">
        <button id="theme-toggle" class="theme-toggle-button">Switch to Dark Mode</button>
    </div>

    <script>
    // Get the theme toggle button and body element
    const themeToggleButton = document.getElementById('theme-toggle');
    const body = document.body;

    // Check saved theme preference in localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        themeToggleButton.textContent = 'Switch to Light Mode';
    }

    // Toggle theme on button click
    themeToggleButton.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        
        // Update button text
        if (body.classList.contains('dark-mode')) {
            themeToggleButton.textContent = 'Switch to Light Mode';
            localStorage.setItem('theme', 'dark'); // Save preference
        } else {
            themeToggleButton.textContent = 'Switch to Dark Mode';
            localStorage.setItem('theme', 'light'); // Save preference
        }
    });
</script>

</body>
</html>
