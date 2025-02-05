<?php
session_start(); // Start the session

// Database connection
include('dbconnection.php');


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Booking</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="bookshow.css" />
</head>

<body>
  <?php
  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
  } else {
    echo '<div class="title-div"></div>
          <div class="mid-div">
            <p class="childrenn">
              Children above 3 Feet will require a separate ticket
            </p>
            <div class="status-container">
              <div class="status">
                <div class="check">
                  <div class="color1"></div>
                  <p>Available</p>
                </div>
                <div class="check">
                  <div class="color2"></div>
                  <p>Selected</p>
                </div>
                <div class="check">
                  <div class="color3"></div>
                  <p>Booked</p>
                </div>
                <div class="check">
                  <div class="color4"></div>
                  <p>Sold</p>
                </div>
              </div>
            </div>
          </div>
          <section class="body">
            <div class="grid">
              <!-- Generate cells A1 to G17 -->
              <div class="cell booked" id="A1">A1</div>
              <div class="cell" id="A2">A2</div>
              <div class="cell" id="A3">A3</div>
              <div class="cell" id="A4">A4</div>
              <div class="cell" id="A5">A5</div>
              <div class="cell booked" id="A6">A6</div>
              <div class="cell" id="A7">A7</div>
              <div class="cell" id="A8">A8</div>
              <div class="cell" id="A9">A9</div>
              <div class="cell" id="A10">A10</div>
              <div class="cell booked" id="A11">A11</div>
              <div class="cell" id="A12">A12</div>
              <div class="cell" id="A13">A13</div>
              <div class="cell" id="A14">A14</div>
              <div class="cell" id="A15">A15</div>
              <div class="cell" id="A16">A16</div>
              <div class="cell" id="A17">A17</div>
              <div class="cell" id="B1">B1</div>
              <div class="cell" id="B2">B2</div>
              <div class="cell" id="B3">B3</div>
              <div class="cell" id="B4">B4</div>
              <div class="cell" id="B5">B5</div>
              <div class="cell" id="B6">B6</div>
              <div class="cell" id="B7">B7</div>
              <div class="cell" id="B8">B8</div>
              <div class="cell" id="B9">B9</div>
              <div class="cell" id="B10">B10</div>
              <div class="cell" id="B11">B11</div>
              <div class="cell" id="B12">B12</div>
              <div class="cell" id="B13">B13</div>
              <div class="cell booked" id="B14 ">B14</div>
              <div class="cell booked" id="B15 ">B15</div>
              <div class="cell booked" id="B16 ">B16</div>
              <div class="cell booked" id="B17 ">B17</div>
              <div class="cell" id="C1">C1</div>
              <div class="cell" id="C2">C2</div>
              <div class="cell" id="C3">C3</div>
              <div class="cell" id="C4">C4</div>
              <div class="cell" id="C5">C5</div>
              <div class="cell" id="C6">C6</div>
              <div class="cell" id="C7">C7</div>
              <div class="cell" id="C8">C8</div>
              <div class="cell" id="C9">C9</div>
              
          
              <div class="cell" id="C10">C10</div>
              <div class="cell booked" id="C11">C11</div>
              <div class="cell booked" id="C12">C12</div>
              <div class="cell booked" id="C13">C13</div>
              <div class="cell" id="C14">C14</div>
              <div class="cell" id="C15">C15</div>
              <div class="cell" id="C16">C16</div>
              <div class="cell" id="C17">C17</div>
              <div class="cell" id="D1">D1</div>
              <div class="cell yellow" id="D2">D2</div>
              <div class="cell yellow" id="D3">D3</div>
              <div class="cell" id="D4">D4</div>
              <div class="cell" id="D5">D5</div>
              <div class="cell" id="D6">D6</div>
              <div class="cell" id="D7">D7</div>
              <div class="cell" id="D8">D8</div>
              <div class="cell" id="D9">D9</div>
              <div class="cell" id="D10">D10</div>
              <div class="cell booked" id="D11">D11</div>
              <div class="cell booked" id="D12">D12</div>
              <div class="cell booked" id="D13">D13</div>
              <div class="cell" id="D14">D14</div>
              <div class="cell" id="D15">D15</div>
              <div class="cell" id="D16">D16</div>
              <div class="cell" id="D17">D17</div>
              <div class="cell" id="E1">E1</div>
              <div class="cell" id="E2">E2</div>
              <div class="cell" id="E3">E3</div>
              <div class="cell" id="E4">E4</div>
              <div class="cell" id="E5">E5</div>
              <div class="cell" id="E6">E6</div>
              <div class="cell" id="E7">E7</div>
              <div class="cell" id="E8">E8</div>
              <div class="cell" id="E9">E9</div>
              <div class="cell" id="E10">E10</div>
              <div class="cell" id="E11">E11</div>
              <div class="cell" id="E12">E12</div>
              <div class="cell" id="E13">E13</div>
              <div class="cell" id="E14">E14</div>
              <div class="cell" id="E15">E15</div>
              <div class="cell" id="E16">E16</div>
              <div class="cell" id="E17">E17</div>
              <div class="cell" id="F1">F1</div>
              <div class="cell" id="F2">F2</div>
              <div class="cell" id="F3">F3</div>
              <div class="cell" id="F4">F4</div>
              <div class="cell" id="F5">F5</div>
              <div class="cell" id="F6">F6</div>
              <div class="cell" id="F7">F7</div>
              <div class="cell" id="F8">F8</div>
              <div class="cell" id="F9">F9</div>
              <div class="cell" id="F10">F10</div>
              <div class="cell" id="F11">F11</div>
              <div class="cell" id="F12">F12</div>
              <div class="cell" id="F13">F13</div>
              <div class="cell" id="F14">F14</div>
              <div class="cell" id="F15">F15</div>
              <div class="cell" id="F16">F16</div>
              <div class="cell" id="F17">F17</div>
              <div class="cell" id="G1">G1</div>
              <div class="cell" id="G2">G2</div>
              <div class="cell yellow" id="G3">G3</div>
              <div class="cell yellow" id="G4">G4</div>
              <div class="cell" id="G5">G5</div>
              <div class="cell" id="G6">G6</div>
              <div class="cell yellow" id="G7">G7</div>
              <div class="cell" id="G8">G8</div>
              <div class="cell" id="G9">G9</div>
              <div class="cell yellow" id="G10">G10</div>
              <div class="cell yellow" id="G11">G11</div>
              <div class="cell yellow" id="G12">G12</div>
              <div class="cell" id="G13">G13</div>
              <div class="cell booked" id="G14">G14</div>
              <div class="cell booked" id="G15">G15</div>
              <div class="cell booked" id="G16">G16</div>
              <div class="cell booked" id="G17">G17</div>
            </div>
          </section>
            <div class="screen">SCREEN</div>

          <div class="form-popup" id="myForm">
            <form class="form-container">
              <h2>alert</h2>
              <p>You have reached your maximum seat booking limit</p>
              <button type="button" class="btn cancel" onclick="closeForm()">
                <span class="close"><i class="fas fa-check"></i> Close</span>
              </button>
            </form>
          </div>
          <hr style="height: 0.2px; background-color: #ddd; border: none" />

          <section id="total">
            Rs.
            <div id="showsum">0</div>
            <button id="proc" onclick="proceedbtn()">Proceed</button>
            <button id="showbtn">Book</button>
          </section>';
  }
  ?>


  <script>
    function openForm() {
      document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
      document.getElementById("myForm").style.display = "none";
    }
  </script>
  <script>
    let getbookdata = localStorage.getItem("bookdetail");
    let parsebookdata = JSON.parse(getbookdata);
    console.log(parsebookdata);


    const date = new Date();

    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();

    // This arrangement can be altered based on how we want the date's format to appear.
    let currentDate = `${year}-${month}-${day}`;
    console.log(currentDate);
    let heading = document.querySelector(".title-div");
    let content = "";
    content = `
            <h2>${parsebookdata.moviename}</h2>
            <p>Cine Nepal Cineplex > Audi -1  ${currentDate}  <span class="set"> ${parsebookdata.time}<span></p>
            `;
    heading.innerHTML = content;
    // Get all elements with the class 'cell'
    const cells = document.querySelectorAll(".grid .cell");
    let selectedCount = 0; // Track the number of selected cells
    let showbtn = document.getElementById('showbtn');
    if (selectedCount > 0) {
      showbtn.style.display = "block";
    } else {
      showbtn.style.display = "none";
    }
    let selectedSeats = []

    function proceedbtn() {
      let finalData = {
        movieName: parsebookdata.moviename,
        movieTime: parsebookdata.time,
        selectedSeat: selectedSeats,
        totalPrice: sum
      }
      if (selectedSeats.length == 0) {
        alert("Please select a seat first");
        return;
      }
      localStorage.setItem("finalData", JSON.stringify(finalData));
      window.location.assign("esewa.php");
    }
    document.addEventListener("DOMContentLoaded", function() {
      const showbtn = document.getElementById("showbtn");
      showbtn.style.display = "none"; // Hide initially if no seats are selected
    });

    cells.forEach((cell) => {
      cell.addEventListener("click", function() {

        if (!selectedSeats.includes(cell.id)) {
          selectedSeats.push(cell.id)
        }
        checkbook(cell.id);
        console.log(cell.id);
      });
    });

    let sum = 0;

    function checkbook(cellId) {
      const cell = document.getElementById(cellId);
      let total1 = document.getElementById("showsum");
      // Check if the cell is already selected (background color is green)
      if (cell.style.backgroundColor === "rgb(76, 175, 80)") {
        // If selected, reset the color and decrement the selected count
        cell.style.backgroundColor = ""; // Reset to default
        cell.style.border = ""; // Reset to default
        selectedCount--;
        if (sum >= 200) {
          sum = sum - 200;
        }
      } else {
        // If the cell is not selected and the limit is not reached, select it
        if (selectedCount < 10) {
          sum = sum + 200;
          cell.style.backgroundColor = "#4CAF50"; // Set to green
          cell.style.border = "#4CAF50"; // Set to green
          selectedCount++;
        }
      }
      total1.innerHTML = sum;

      // Disable further selection if the limit is reached
      if (selectedCount >= 10) {
        document.getElementById("myForm").style.display = "block";

        cells.forEach((cell) => {
          if (cell.style.backgroundColor !== "rgb(76, 175, 80)") {
            cell.removeEventListener("click", function() {
              checkbook(cell.id);
            });
          }
        });
      }
    }
  </script>
</body>

</html>