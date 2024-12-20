
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_FILES["video"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["video"]["name"]);

        if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
            echo "Video uploaded successfully!";
        } else {
            echo "Failed to upload video.";
        }
    } else {
        echo "No video file uploaded.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Upload Test</title>
</head>
<body>
    <form action="upload_test.php" method="post" enctype="multipart/form-data">
        <input type="file" name="video" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
