<?php
session_start(); // Start the session

// Database connection
include('dbconnection.php');

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
                       <img src="Images/logo12.png"  alt="" />
                </div>
                <ul>
                    <li class="active"><a href="index.php">Home</a></li>
                    <li>My Tickets</li>
                    <li><a href="booking.php">Booking</a></li>
                    <li><a href="movie.php">Movie</a></li>
                    <li><a href="ticket.php">Ticket Rate</a></li>
                </ul>
                <div class="after">
                <ion-icon name="person-circle-outline" class="icon"></ion-icon>
                <h2 class="session-user">' . htmlspecialchars($_SESSION["email"]) . '</h2>
                <div>
                <div class="logout">
                    <button><a href="?logout=true">Logout</a></button>
                </div>
            </nav>';
        }
        ?>
    </section>
    <div id="movie-container">
        <div class="container">
            <div class="child-container"></div>
        </div>
        <section id="seat" style="color: white;">
            <div class="seat-container">
                <div class="title">
                    <h1>Viewing Times</h1>
                </div>
                <div class="dates">
                    <span class="today">TODAY</span>
                    <span>TOMM</span>
                    <span>14 AUG</span>
                    <span>15 AUG</span>
                    <span>16 AUG</span>
                    <span>17 AUG</span>
                    <span>18 AUG</span>

                </div>
            </div>
    </div>
    </section>
    <div class="time">
        <div class="audi">Audi-1</div>
        <div class="free-time"></div>
    </div>
    </div>


    <script>
        let getvalue = localStorage.getItem("moviedetail");
        let parsedData = JSON.parse(getvalue);
        window.onload = displayResult();

        function displayResult() {
            let showdiv = document.querySelector(".child-container");
            let free_time = document.querySelector(".free-time");
            let content = "";
            content = `
        <div class="imagediv">
            <img src="${parsedData.image}">
            </div>
       <div class="movie-description">
  <h1>${parsedData.title}</h1>
  <p class="genere">${parsedData.genere}</p>
  <p class="movdes">${parsedData.description}</p>
  <div class="more-detail">
    <div class="detail-item">
      <span class="label">Director:</span>
      <span class="value">${parsedData.director}</span>
    </div>
    <div class="detail-item">
      <span class="label">Cast:</span>
      <span class="value">${parsedData.cast}</span>
    </div>
    <div class="detail-item">
      <span class="label">Release On:</span>
      <span class="value">${parsedData.releaseon}</span>
    </div>
    <div class="detail-item">
      <span class="label">Duration:</span>
      <span class="value">${parsedData.duration}</span>
    </div>
  </div>
</div>
        `;
            showdiv.innerHTML = content;
            free_time.innerHTML += `${showTime(parsedData.available_time,parsedData.title)}`

            function showTime(times, title) {
                return times.map((time) =>
                    `<span><button onclick="show('${title}', '${time}');">${time}</button></span>`
                ).join("");
            }
        }

        function show(title, time) {

            let bookdetail = {
                "moviename": title,
                "time": time
            };
            localStorage.setItem('bookdetail', JSON.stringify(bookdetail));
            window.location.href = "bookshow.php";
        }
    </script>
</body>

</html>