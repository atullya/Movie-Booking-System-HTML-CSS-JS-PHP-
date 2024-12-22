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
        <aside id="sidebar">
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
        <div class="logout-container" style="margin: auto;">
            <button style="
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  background-color: #ff4d4f;
  color: white;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s, transform 0.2s;
"
                class="logout-btn" onclick="window.location.href='login.php'">
                Logout
            </button>

        </div>
    </div>

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