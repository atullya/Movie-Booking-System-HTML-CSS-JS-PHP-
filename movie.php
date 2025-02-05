<?php
session_start(); // Start the session

// Database connection
include('dbconnection.php');
$sql = "Select * from users";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Handle logout
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cine Nepal Cineplex</title>
  <link rel="stylesheet" href="styles.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="movie.css" />
</head>

<body class="body">
  <section id="header">
    <?php
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
      echo '
            <nav>
                <div class="logo">
                   <a href="index.php">
                      <img src="Images/logo12.png"  alt="" />
                      </a>
                </div>
                <ul>
                    <li class="active"><a href="index.php">Home</a></li>
                    <li>Movie</li>
                    <li><a href="ticket.php">Ticket Rate</a></li>
                </ul>
                <div class="login">
                    <button><a href="login.php">Login</a></button>
                </div>
            </nav>';
    } else {
      echo '
            <nav>
                <div class="logo">
             <a href="index.php">
                      <img src="Images/logo12.png"  alt="" />
                      </a>
                </div>
                <ul>
                    <li ><a href="index.php">Home</a></li>
                    <li>My Tickets</li>
                    <li class="active"><a href="booking.php">Booking</a></li>
                    <li><a href="movie.php">Movie</a></li>
                    <li><a href="ticket.php">Ticket Rate</a></li>
                </ul>
                <div class="after">
                    <ion-icon name="person-circle-outline" class="icon"></ion-icon>
                    <a href="user.php?uid=' . $row["uid"] . '">
                        <h2 class="session-user">' . htmlspecialchars($_SESSION["email"]) . '</h2>
                    </a>
                </div>
                <div class="logout">
                    <button><a href="?logout=true">Logout</a></button>
                </div>
            </nav>';
    }
    ?>
  </section>

  <div class="tab-titles">
    <p class="tab-links active-link" onclick="opentab('trending'); getData()">
      Popular
    </p>
    <p class="tab-links" onclick="opentab('upcoming'); upcoming()">
      Upcoming
    </p>
  </div>
  <div class="tab-contents active-tab" id="trending">
    <div id="show"></div>
  </div>

  <div class="tab-contents" id="upcoming">
    <div id="upcoming-show"></div>
  </div>

  <!-- The Modal -->
  <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <span class="close">&times;</span>
      <div id="modal-video-container"></div>
    </div>
  </div>

  <script>
    let tablinks = document.getElementsByClassName("tab-links");
    let tabcontents = document.getElementsByClassName("tab-contents");
    let allData = [];

    function opentab(tabname) {
      for (let tablink of tablinks) {
        tablink.classList.remove("active-link");
      }
      for (let tabcontent of tabcontents) {
        tabcontent.classList.remove("active-tab");
      }

      event.currentTarget.classList.add("active-link");
      document.getElementById(tabname).classList.add("active-tab");
    }

    async function getData() {
      let response = await fetch(
        "https://api.themoviedb.org/3/movie/popular?api_key=4e44d9029b1270a757cddc766a1bcb63&language=en-US"
      );
      let data = await response.json();
      allData = data.results;
      display(data.results);
    }

    function display(data) {
      let content = "";
      let showmovie = document.getElementById("show");

      data.forEach((element, index) => {
        content += `
                      <div class="movies">
                          <img src="https://image.tmdb.org/t/p/w500${element.poster_path}">
                          <p class="title">${element.title}</p>
                          <p class="duration">Released Date:${element.release_date}</p>
                          <div class="layer">
                              <button class="btn trailer" onclick="trailer(event,${index})">Trailer</button>
                              <button class="btn" onclick="detail(event, ${index})">View Detail</button>
                          </div>
                      </div>`;
      });

      showmovie.innerHTML = content;
    }

    async function upcoming() {
      let response = await fetch(
        "https://api.themoviedb.org/3/movie/upcoming?api_key=4e44d9029b1270a757cddc766a1bcb63&language=en-US"
      );
      let data = await response.json();
      allData = data.results;
      display1(data.results);
    }

    function display1(data) {
      let content = "";
      let showmovie = document.getElementById("upcoming-show");

      data.forEach((element, index) => {
        content += `
                  <div class="movies">
                      <img src="https://image.tmdb.org/t/p/w500${element.poster_path}">
                      <p class="title">${element.title}</p>
                      <p class="duration">Released Date:${element.release_date}</p>
                      <div class="layer">
                          <button class="btn trailer" onclick="trailer(event,${index})">Trailer</button>
                          <button class="btn" onclick="detail(event, ${index})">View Detail</button>
                      </div>
                  </div>`;
      });

      showmovie.innerHTML = content;
    }

    function trailer(event, index) {
      let selectedmovie = allData[index];
      const apiKey = "4e44d9029b1270a757cddc766a1bcb63";
      const movieId = selectedmovie.id;

      fetch(
          `https://api.themoviedb.org/3/movie/${movieId}/videos?api_key=${apiKey}&language=en-US`
        )
        .then((response) => response.json())
        .then((data) => {
          const videoKey = data.results[0]?.key;
          if (videoKey) {
            displayVideo(videoKey);
          } else {
            document.getElementById("modal-video-container").innerText =
              "No video found.";
          }
        })
        .catch((error) => {
          console.error("Error fetching movie data:", error);
          document.getElementById("modal-video-container").innerText =
            "Error fetching video.";
        });

      event.preventDefault(); // Prevent default button action
    }

    function displayVideo(videoKey) {
      const videoUrl = `https://www.youtube.com/embed/${videoKey}`;
      const iframe = document.createElement("iframe");
      iframe.src = videoUrl;
      iframe.width = "70%";
      iframe.height = "315";
      iframe.frameBorder = "0";
      iframe.allow =
        "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
      iframe.allowFullscreen = true;

      // Clear previous content in modal
      const modalVideoContainer = document.getElementById(
        "modal-video-container"
      );
      modalVideoContainer.innerHTML = "";
      modalVideoContainer.appendChild(iframe);

      // Show modal
      document.getElementById("myModal").style.display = "block";

      // Add click event to close the modal
      document.querySelector(".close").onclick = function() {
        document.getElementById("myModal").style.display = "none";

        // Remove the iframe to stop the video from playing
        modalVideoContainer.innerHTML = "";
      };

      // Close modal if user clicks outside of modal content
    }

    function detail(event, index) {
      let selectedmovie = allData[index];
      localStorage.setItem("selectedMovies", JSON.stringify(selectedmovie));
      window.location.href = "moooviedetail.html";
    }

    getData();
  </script>
  <script
    type="module"
    src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script
    nomodule
    src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="index.js"></script>
</body>

</html>