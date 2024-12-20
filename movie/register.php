<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]); 
    $sql = "INSERT INTO users (full_name, username, password) VALUES ('$full_name', '$username', '$password')";
    if (mysqli_query($link, $sql)) {
        echo "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . mysqli_error($link);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Register</h2>
    <form method="post">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
</body>
</html>
