<?php
// Database connection settings
$server = "localhost";
$username = "root";
$password = "";
$dbname = "movie_website";

// Connect to MySQL server
$link = mysqli_connect($server, $username, $password);

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($link, $sql)) {
    echo "Database created successfully.<br>";
} else {
    die("ERROR: Could not create database. " . mysqli_error($link));
}

// Select database
mysqli_select_db($link, $dbname);

// Create `users` table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL
)";
if (mysqli_query($link, $sql)) {
    echo "Table 'users' created successfully.<br>";
} else {
    die("ERROR: Could not create table 'users'. " . mysqli_error($link));
}

// Create `movies` table
$sql = "CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    video_path VARCHAR(255) NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
if (mysqli_query($link, $sql)) {
    echo "Table 'movies' created successfully.<br>";
} else {
    die("ERROR: Could not create table 'movies'. " . mysqli_error($link));
}

// Close connection
mysqli_close($link);
?>
