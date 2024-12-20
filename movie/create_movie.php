<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (isset($_FILES["video"])) {
        error_log("DEBUG: Video name: " . $_FILES["video"]["name"]);
        error_log("DEBUG: Video size: " . $_FILES["video"]["size"]);
        error_log("DEBUG: Video tmp_name: " . $_FILES["video"]["tmp_name"]);
    } else {
        error_log("DEBUG: No video file uploaded.");
    }

    if (!empty($_FILES["video"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["video"]["name"]);

        if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
            $title = $_POST["title"];
            $description = $_POST["description"];
            $rating = $_POST["rating"];

            $sql = "INSERT INTO movies (title, description, rating, video_path) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "ssis", $title, $description, $rating, $target_file);

            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(["status" => "success", "message" => "Movie uploaded successfully!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database insertion failed."]);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to upload video file."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No video file uploaded."]);
    }
}
?>
