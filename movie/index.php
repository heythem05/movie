<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["loggedin"])) {
    header("location: login.php");
    exit;
}

$full_name = $_SESSION["full_name"];

$success_message = "";
if (!empty($_SESSION['login_success'])) {
    $success_message = $_SESSION['login_success'];
    unset($_SESSION['login_success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <h2>Welcome, <?php echo htmlspecialchars($full_name); ?>!</h2>
    <button id="addMovieBtn" class="btn btn-success mt-3" data-toggle="modal" data-target="#movieModal">Add New Movie</button>
    <a href="logout.php" class="btn btn-danger mt-3 ml-2">Logout</a>
    <div id="movieList" class="mt-5 row"></div>
</div>


<div class="modal fade" id="movieModal" tabindex="-1" role="dialog" aria-labelledby="movieModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="movieForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="movieModalLabel">Add New Movie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating</label>
                        <input type="number" id="rating" name="rating" class="form-control" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label for="video">Video</label>
                        <input type="file" id="video" name="video" class="form-control" accept="video/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    loadMovies();

    function loadMovies() {
        $.get("fetch_movies.php", function (data) {
            let movies = JSON.parse(data);
            let html = "";
            if (movies.length > 0) {
                movies.forEach(function (movie) {
                    html += `
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <video class="card-img-top" controls>
                                <source src="${movie.video_path}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="card-body">
                                <h5 class="card-title">${movie.title}</h5>
                                <p class="card-text">${movie.description}</p>
                                <p><strong>Rating:</strong> ${movie.rating}/5</p>
                                <button class="btn btn-danger deleteBtn" data-id="${movie.id}">Delete</button>
                            </div>
                        </div>
                    </div>`;
                });
            } else {
                html = `<p class="text-center">No movies added yet. Use the "Add New Movie" button to get started!</p>`;
            }
            $("#movieList").html(html);
        });
    }

    $("#movieForm").submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "create_movie.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                let res = JSON.parse(response);
                if (res.status === "success") {
                    Swal.fire("Success", res.message, "success");
                    $("#movieModal").modal("hide");
                    $("#movieForm")[0].reset();
                    loadMovies();
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Failed to submit the form. Please try again.", "error");
            },
        });
    });

    $(document).on("click", ".deleteBtn", function () {
        let movieId = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to undo this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("delete_movie.php", { id: movieId }, function (response) {
                    let res = JSON.parse(response);
                    if (res.status === "success") {
                        Swal.fire("Deleted!", res.message, "success");
                        loadMovies();
                    } else {
                        Swal.fire("Error", res.message, "error");
                    }
                });
            }
        });
    });
});
</script>
</body>
</html>
