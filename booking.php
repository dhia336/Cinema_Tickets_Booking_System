<?php
error_reporting(E_ALL & ~E_NOTICE); // This will suppress only notices, not warnings or errors
// booking.php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['movie_id'])) {
    header("Location: index.php");
    exit();
}

$movie_id = $_GET['movie_id'];
$showtimes = $conn->prepare("SELECT * FROM Showtimes WHERE movie_id = :movie_id");
$showtimes->bindParam(':movie_id', $movie_id);
$showtimes->execute();
$showtimes = $showtimes->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['showtime_id'], $_POST['seats'])) {
    $showtime_id = $_POST['showtime_id'];
    $seats = $_POST['seats'];
    $user_id = $_SESSION['user_id'];

    // Prepare placeholders for seat checking
    $seatPlaceholdersArray = array_fill(0, count($seats), '?');
    $seatPlaceholders = implode(',', $seatPlaceholdersArray);  // Store the result of implode in a variable

    // Check if any of the selected seats are already booked by the same user for this showtime
    $duplicateSeats = [];
    foreach ($seats as $seat) {
        $query = "SELECT * FROM Bookings WHERE showtime_id = ? AND user_id = ? AND JSON_CONTAINS(seats, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$showtime_id, $user_id, json_encode([$seat])]);
        
        if ($stmt->rowCount() > 0) {
            $duplicateSeats[] = $seat;
        }
    }

    if (!empty($duplicateSeats)) {
        $error = "You have already booked seat(s): " . implode(', ', $duplicateSeats) . " for this showtime.";
    } else {
        // If no duplicate seats, proceed with the booking
        $stmt = $conn->prepare("INSERT INTO Bookings (user_id, showtime_id, seats, booking_time, payment_status) VALUES (:user_id, :showtime_id, :seats, NOW(), 'paid')");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':showtime_id', $showtime_id);
        $stmt->bindParam(':seats', json_encode($seats));  // Bind the JSON-encoded seats properly
        
        if ($stmt->execute()) {
            $success = "Booking successful!";
        } else {
            $error = "Booking failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Movie</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="booking-container">
        <h2>Book Your Seats</h2>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <form method="POST" action="">
            <label for="showtime">Select Showtime:</label>
            <select name="showtime_id" id="showtime" required>
                <?php foreach ($showtimes as $showtime): ?>
                    <option value="<?php echo $showtime['id']; ?>">
                        <?php echo $showtime['show_date'] . ' - ' . $showtime['show_time'] . ' (Hall ' . $showtime['hall_number'] . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Select Seats:</label>
            <div class="seat-selection">
                <input type="checkbox" name="seats[]" value="A1"> A1
                <input type="checkbox" name="seats[]" value="A2"> A2
                <input type="checkbox" name="seats[]" value="A3"> A3
                <input type="checkbox" name="seats[]" value="A4"> A4
                <input type="checkbox" name="seats[]" value="A5"> A5
            </div>

            <button type="submit">Confirm Booking</button>
        </form>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
