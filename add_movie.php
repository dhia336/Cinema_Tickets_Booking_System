<?php
session_start();
require 'db_config.php';

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

// Handle add showtime form submission
if (isset($_POST['add_showtime'])) {
    $movie_id = $_POST['movie_id'];
    $show_date = $_POST['show_date'];
    $show_time = $_POST['show_time'];

    $stmt = $conn->prepare("INSERT INTO Showtimes (movie_id, show_date, show_time) 
                            VALUES (:movie_id, :show_date, :show_time)");
    $stmt->bindParam(':movie_id', $movie_id);
    $stmt->bindParam(':show_date', $show_date);
    $stmt->bindParam(':show_time', $show_time);

    if ($stmt->execute()) {
        $showtime_success_message = "Showtime added successfully!";
    } else {
        $showtime_error_message = "Error adding showtime!";
    }
}

// Handle movie delete functionality
if (isset($_POST['delete_movie'])) {
    $movie_title = $_POST['movie_title'];

    // Prepare SQL to delete movie
    $stmt = $conn->prepare("DELETE FROM Movies WHERE title = :title");
    $stmt->bindParam(':title', $movie_title);

    if ($stmt->execute()) {
        $delete_success_message = "Movie deleted successfully!";
    } else {
        $delete_error_message = "Error deleting movie!";
    }
}

// Fetch all movies for the delete dropdown
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
    <style>
        .success {
    color: green;
}

.error {
    color: red;
}

form {
    margin: 20px 0;
}

label {
    display: block;
    margin-bottom: 5px;
}

input, textarea, select, button {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 4px;
}

button {
    background-color: #28a745;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #218838;
}

    </style>
</head>
<body>

    <h1>Add Movie</h1>

    <!-- Add Movie Form -->
    <form method="POST">
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

    <?php if (isset($success_message)): ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Add Showtime Form (only after movie is added) -->
    <?php if (isset($movie_id)): ?>
        <h2>Add Showtime</h2>
        <form method="POST">
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
            <label for="show_date">Show Date:</label>
            <input type="date" name="show_date" id="show_date" required>

            <label for="show_time">Show Time:</label>
            <input type="time" name="show_time" id="show_time" required>

            <button type="submit" name="add_showtime">Add Showtime</button>
        </form>

        <?php if (isset($showtime_success_message)): ?>
            <p class="success"><?php echo $showtime_success_message; ?></p>
        <?php endif; ?>

        <?php if (isset($showtime_error_message)): ?>
            <p class="error"><?php echo $showtime_error_message; ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Delete Movie Form -->
    <h2>Delete Movie</h2>
    <form method="POST">
        <label for="movie_title">Select Movie to Delete:</label>
        <select name="movie_title" id="movie_title" required>
            <option value="">Select a Movie</option>
            <?php foreach ($movies as $movie): ?>
                <option value="<?php echo $movie['title']; ?>"><?php echo $movie['title']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="delete_movie">Delete Movie</button>
    </form>

    <?php if (isset($delete_success_message)): ?>
        <p class="success"><?php echo $delete_success_message; ?></p>
    <?php endif; ?>

    <?php if (isset($delete_error_message)): ?>
        <p class="error"><?php echo $delete_error_message; ?></p>
    <?php endif; ?>

    <div class="logout-container">
        <a href="index.php" class="logout-button">Logout</a>
    </div>

</body>
</html>
