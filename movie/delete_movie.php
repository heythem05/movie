<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_id = intval($_POST["id"]);
    $sql = "DELETE FROM movies WHERE id = $movie_id";

    if (mysqli_query($link, $sql)) {
        echo json_encode(["status" => "success", "message" => "Movie deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($link)]);
    }
}
?>
