<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization");

// Database configuration
$host = "sql207.infinityfree.com";
$username = "if0_40764182";
$password = "W2TyvlO7PEmE";
$dbname = "ranjana";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET': // Fetch movies
        if (isset($_GET['id'])) {
            // Fetch a single movie by ID
            $id = intval($_GET['id']);
            $result = $conn->query("SELECT * FROM movies WHERE movie_id = $id");

            if ($result->num_rows > 0) {
                echo json_encode($result->fetch_assoc());
            } else {
                echo json_encode(["status" => "error", "message" => "Movie not found"]);
            }
        } else {
            // Fetch all movies
            $result = $conn->query("SELECT * FROM movies");
            $movies = [];
            while ($row = $result->fetch_assoc()) {
                $row['available_time'] = json_decode($row['available_time']);
                $movies[] = $row;
            }
            echo json_encode($movies);
        }
        break;

    case 'POST': // Add a new movie with images and videos
        if (isset($_POST['title'], $_POST['duration'], $_POST['available_time'], $_POST['genere'], $_POST['description'], $_POST['director'], $_POST['cast'], $_POST['releaseon'], $_FILES['image'], $_FILES['vid'])) {

            // Check for file upload errors
            if ($_FILES['image']['error'] != UPLOAD_ERR_OK || $_FILES['vid']['error'] != UPLOAD_ERR_OK) {
                echo json_encode(["status" => "error", "message" => "File upload error"]);
                exit();
            }

            // Define allowed file types
            $allowedImageTypes = ['image/jpeg', 'image/png'];
            $allowedVideoTypes = ['video/mp4', 'video/avi'];

            // Get the uploaded files
            $image = $_FILES['image'];
            $video = $_FILES['vid'];

            // Check file types
            if (!in_array($image['type'], $allowedImageTypes) || !in_array($video['type'], $allowedVideoTypes)) {
                echo json_encode(["status" => "error", "message" => "Invalid file type"]);
                exit();
            }

            // Set the file paths
            $imagePath = 'uploads/' . basename($image['name']);
            $videoPath = 'uploads/' . basename($video['name']);

            // Move the uploaded files to the server
            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                echo json_encode(["status" => "error", "message" => "Failed to upload image"]);
                exit();
            }

            if (!move_uploaded_file($video['tmp_name'], $videoPath)) {
                echo json_encode(["status" => "error", "message" => "Failed to upload video"]);
                exit();
            }

            // Insert movie data into the database
            $stmt = $conn->prepare("INSERT INTO movies (title, duration, available_time, genere, description, director, cast, release_on, image, vid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "ssssssssss",
                $_POST['title'],
                $_POST['duration'],
                json_encode(explode(',', $_POST['available_time'])),
                $_POST['genere'],
                $_POST['description'],
                $_POST['director'],
                $_POST['cast'],
                $_POST['releaseon'],
                $imagePath,
                $videoPath
            );

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Movie added successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to add movie"]);
            }

            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Missing required fields or files"]);
        }
        break;

    case 'PUT': // Update an existing movie
        parse_str(file_get_contents("php://input"), $_PUT);
        if (isset($_GET['id']) && !empty($_PUT)) {
            $id = intval($_GET['id']);

            $stmt = $conn->prepare("UPDATE movies SET title=?, duration=?, available_time=?, genere=?, description=?, director=?, cast=?, release_on=? WHERE movie_id=?");
            $stmt->bind_param(
                "ssssssssi",
                $_PUT['title'],
                $_PUT['duration'],
                json_encode(explode(',', $_PUT['available_time'])),
                $_PUT['genere'],
                $_PUT['description'],
                $_PUT['director'],
                $_PUT['cast'],
                $_PUT['releaseon'],
                $id
            );

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Movie updated successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update movie"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid input or missing ID"]);
        }
        break;

    case 'DELETE': // Delete a movie
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);

            $result = $conn->query("SELECT image, vid FROM movies WHERE movie_id = $id");
            if ($result->num_rows > 0) {
                $movie = $result->fetch_assoc();
                if (file_exists($movie['image'])) unlink($movie['image']);
                if (file_exists($movie['vid'])) unlink($movie['vid']);
            }

            if ($conn->query("DELETE FROM movies WHERE movie_id = $id")) {
                echo json_encode(["status" => "success", "message" => "Movie deleted successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to delete movie"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid ID"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Unsupported HTTP method"]);
        break;
}

$conn->close();
