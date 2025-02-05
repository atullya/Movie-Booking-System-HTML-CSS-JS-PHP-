<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Movie Management</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar" class="sid">
            <div>
                <img src="Images/logo12.png" height="110px" width="140px" style="margin: auto; margin-left:70px" alt="Logo" />
                <h2 style="  text-align: center;">Admin Panel</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="adminhome.php"><i class="fa-solid fa-house" style="margin-right:12px"></i>Home</a></li>
                    <li><a href="adminupload.php" id="addMovieLink" style="margin-right:18px"> <i class="fa-solid fa-clapperboard"></i> Add Movies</a></li>
                    <li><a href="adminlistalluser.php" style="margin-right:18px"><i class="fa-solid fa-users"></i> List All Users</a></li>
                    <li><a href="adminlogout.php"> <i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div id="main-content">
            <h1>Admin Movie Management</h1>
            <button id="loadMovies" class="custom-btn">Load Movies</button>

            <div id="movieList"></div>

            <h2 style="display: block; margin: 0 auto; text-align: center;">Add New Movie</h2>
            <form id="movieForm" enctype="multipart/form-data">
                <input type="text" value="Avatar: Fire and Ash" id="title" placeholder="Title" required><br>
                <input type="text" value="3hr" id="duration" placeholder="Duration" required><br>
                <input type="text" value="6:00 AM, 11:00 AM, 4:00PM" id="available_time" placeholder="Available Time (comma-separated)" required><br>
                <input type="text" value="Adventure" id="genere" placeholder="Genre" required><br>
                <textarea id="description" placeholder="Description" required>
Avatar: Fire and Ash is an upcoming American epic science fiction film co-produced, co-edited, co-written, and directed by James Cameron
</textarea>
                <input type="text" value="James Cameron" id="director" placeholder="Director" required><br>
                <input type="text" value="Sam Worthington, Zoe Saldana, Kate Winslet" id="cast" placeholder="Cast" required><br>
                <input type="date" value="2025-12-01" id="releaseon" placeholder="Release Date" required><br>

                <!-- Image File Upload -->
                <input type="file" id="image" accept="image/*" required><br>
                <img id="preview" style="  width: 300px;
      height: 300px;
      object-fit: cover;
      border: 2px solid #ddd;
      border-radius: 5px;
      margin-top: 10px;
      display: none;" alt="Image Preview">
                <!-- Video File Upload -->
                <input type="file" id="vid" accept="video/*" required><br>

                <button type="submit" id="addMovieButton">Add Movie</button>
            </form>
        </div>
    </div>
    <script>
        const imageInput = document.getElementById('image');
        const preview = document.getElementById('preview');
        document.getElementById("addMovieButton").addEventListener("click", function() {
            setTimeout(function() {
                alert("Movie has been added successfully!");
            }, 3000);
        });
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0]; // Get the uploaded file
            if (file) {
                const reader = new FileReader(); // Create a FileReader to read the file
                reader.onload = function(e) {
                    preview.src = e.target.result; // Set the preview image source
                    preview.style.display = 'block'; // Show the preview image
                };
                reader.readAsDataURL(file); // Read the file as a Data URL
            } else {
                preview.src = ''; // Clear the preview if no file is selected
                preview.style.display = 'none';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load movies from the backend API
            document.getElementById('loadMovies').addEventListener('click', () => {
                fetch('http://localhost/ranjana/clone/RestAPI.php')
                    .then(response => response.json())
                    .then(data => {
                        const movieList = document.getElementById('movieList');
                        movieList.innerHTML = ''; // Clear previous list
                        data.forEach(movie => {
                            const div = document.createElement('div');
                            div.innerHTML = `
                                <h3>${movie.title}</h3>
                                <p>${movie.description}</p>
                                <img src="${movie.image}" alt="${movie.title}" style="max-width: 100%; height: auto;">
                                <button onclick="deleteMovie(${movie.movie_id})">Delete</button>
                            `;
                            movieList.appendChild(div);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading movies:', error);
                    });
            });

            // Function to delete a movie
            window.deleteMovie = function(id) {
                fetch(`http://localhost/ranjana/clone/RestAPI.php?id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload(); // Reload the page after deletion
                    })
                    .catch(error => {
                        console.error('Error deleting movie:', error);
                    });
            }

            // Handling the form submission to add a movie
            document.getElementById('movieForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData();
                const title = document.getElementById('title').value;
                const duration = document.getElementById('duration').value;
                const availableTime = document.getElementById('available_time').value.split(',');
                const genere = document.getElementById('genere').value;
                const description = document.getElementById('description').value;
                const director = document.getElementById('director').value;
                const cast = document.getElementById('cast').value;
                const releaseon = document.getElementById('releaseon').value;
                const image = document.getElementById('image').files[0];
                const vid = document.getElementById('vid').files[0];

                // Validation for required fields
                if (!title || !duration || !availableTime || !genere || !description || !director || !cast || !releaseon || !image || !vid) {
                    alert("All fields are required!");
                    return;
                }

                // Append the form data
                formData.append('title', title);
                formData.append('duration', duration);
                formData.append('available_time', JSON.stringify(availableTime)); // Ensure available_time is an array
                formData.append('genere', genere);
                formData.append('description', description);
                formData.append('director', director);
                formData.append('cast', cast);
                formData.append('releaseon', releaseon);
                formData.append('image', image);
                formData.append('vid', vid);

                // Send form data to the backend
                fetch('http://localhost/ranjana/clone/RestAPI.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        location.reload(); // Reload the page after successful submission
                    })
                    .catch(error => {
                        console.error('Error adding movie:', error);
                    });
            });
        });
    </script>
</body>

</html>