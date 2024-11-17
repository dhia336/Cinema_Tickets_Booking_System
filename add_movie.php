<?php
session_start();
require 'db_config.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$userStmt = $conn->prepare("SELECT role FROM Users WHERE id = :user_id");
$userStmt->bindParam(':user_id', $user_id);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin') {
    header("Location: movies.php"); // Redirect if not an admin
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];
    $poster_url = $_POST['poster_url'];
    $trailer_url = $_POST['trailer_url'];

    // Validate inputs
    if (empty($title) || empty($genre) || empty($duration) || empty($description) || empty($poster_url) || empty($trailer_url)) {
        $error = "All fields are required!";
    } else {
        // Insert movie into the database
        $stmt = $conn->prepare("INSERT INTO Movies (title, genre, duration, description, poster_url, trailer_url) 
                                VALUES (:title, :genre, :duration, :description, :poster_url, :trailer_url)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':duration', $duration);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':poster_url', $poster_url);
        $stmt->bindParam(':trailer_url', $trailer_url);
        
        if ($stmt->execute()) {
            $success = "Movie added successfully!";
        } else {
            $error = "Failed to add movie. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
    <style>
        /* styles.css */

/* Form container */
.form-container {
    width: 60%;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 1.1em;
    margin: 10px 0 5px;
}

input, textarea {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

textarea {
    resize: vertical;
}

button {
    padding: 10px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #218838;
}

.error {
    color: red;
    font-size: 1em;
    margin-bottom: 20px;
}

.success {
    color: green;
    font-size: 1em;
    margin-bottom: 20px;
}

.back-to-movies {
    text-align: center;
    margin-top: 20px;
}

.back-button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
}

.back-button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <h1>Add New Movie</h1>

    <div class="form-container">
        <form action="add_movie.php" method="POST">
            <?php if (isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php elseif (isset($success)): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>
            
            <label for="title">Movie Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" required>

            <label for="duration">Duration (mins):</label>
            <input type="number" id="duration" name="duration" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="poster_url">Poster URL:</label>
            <input type="url" id="poster_url" name="poster_url" required>

            <label for="trailer_url">Trailer URL:</label>
            <input type="url" id="trailer_url" name="trailer_url" required>

            <button type="submit">Add Movie</button>
        </form>
    </div>

    <div class="back-to-movies">
        <a href="movies.php" class="back-button">Back to Movies</a>
    </div>
</body>
</html>
