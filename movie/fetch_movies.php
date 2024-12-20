<?php
session_start();
require_once "config.php";


if (!isset($_SESSION["loggedin"])) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}


$sql = "SELECT * FROM movies";
$result = mysqli_query($link, $sql);

$movies = [];
while ($row = mysqli_fetch_assoc($result)) {
   
    $row['video_path'] = htmlspecialchars($row['video_path']);
    $movies[] = $row;
}

// Return movies as JSON
echo json_encode($movies);
?>
