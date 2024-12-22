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
<?php
// Function to generate the eSewa signature
function generateEsewaSignature($secretKey, $amount, $transactionUuid, $merchantCode)
{
    // Prepare the signature string as required by eSewa
    $signatureString = "total_amount=$amount,transaction_uuid=$transactionUuid,product_code=$merchantCode";

    // Generate the HMAC SHA256 hash
    $hash = hash_hmac('sha256', $signatureString, $secretKey, true);

    // Convert the hash to Base64
    return base64_encode($hash);
}

// Payment initiation logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $amount = $_POST['amount'] ?? '';
    $productName = $_POST['productName'] ?? '';
    $transactionId = $_POST['transactionId'] ?? '';

    // Validate input
    if (empty($amount) || empty($productName) || empty($transactionId)) {
        die('Missing required parameters.');
    }

    // eSewa Configuration
    $merchantCode = "EPAYTEST"; // Replace with your actual merchant code
    $secretKey = "8gBm/:&EnhH.1/q"; // Replace with your actual secret key
    $successUrl = "http://localhost/ranjana/clone/afterbookshow.php"; // Replace with your success URL
    $failureUrl = "http://yourdomain.com/payment-failure"; // Replace with your failure URL
    $transactionUuid = uniqid("txn-", true); // Unique identifier for the transaction

    // Generate the signature
    $signature = generateEsewaSignature($secretKey, $amount, $transactionUuid, $merchantCode);

    // Prepare eSewa payment parameters
    $esewaConfig = [
        "amount" => $amount,
        "tax_amount" => "0",
        "total_amount" => $amount,
        "transaction_uuid" => $transactionUuid,
        "product_code" => $merchantCode,
        "product_service_charge" => "0",
        "product_delivery_charge" => "0",
        "success_url" => $successUrl,
        "failure_url" => $failureUrl,
        "signed_field_names" => "total_amount,transaction_uuid,product_code",
        "signature" => $signature,
    ];

    // eSewa Payment URL
    $paymentUrl = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";
    // $paymentUrl = "https://epay.esewa.com.np/api/epay/main/v2/form";

    // Redirect to eSewa payment gateway
    echo "<form id='esewaForm' method='POST' action='$paymentUrl'>";
    foreach ($esewaConfig as $key => $value) {
        echo "<input type='hidden' name='$key' value='$value'>";
    }
    echo "</form>";
    echo "<script>document.getElementById('esewaForm').submit();</script>";
} else {
    // Show a sample form to initiate payment
?>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="styles.css" />

    <body>
        <section id="header">
            <?php
            if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
                echo '
            <nav>
                <div class="logo">
                     <img src="Images/logo12.png"  alt="" />
                </div>
                <ul>
                    <li class="active"><a href="index.php">Home</a></li>
                    <li ><a href="movie.php">Movie</a></li>
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
        <section id="esewa">
            <div class="e-container">

                <div class='summary-container'>
                    <div id="show"></div>

                </div>

                <form method="POST" action="" id="esewa-form">
                    <h2>Tickets</h2>
                    <div id="tic1">

                        <label for="amount">Sub total:</label>
                        <input type="text" id="amount" name="amount" style="cursor:pointer" readonly required><br>
                    </div>
                    <div id="tic1">
                        <label for="amount1">Total:</label>
                        <input type="text" id="amount1" name="amount1" style="cursor:pointer" readonly required><br>
                    </div>


                    <div style="display:none">
                        <label for="productName">Product Name:</label>
                        <input type="text" id="productName" value="1asdf2" name="productName" required><br>

                        <label for="transactionId">Transaction ID:</label>
                        <input type="text" id="transactionId" value="12" name="transactionId" required><br>
                    </div>
                    <button type="submit">Pay with eSewa</button>
                </form>
            </div>
            <section>
    </body>
<?php
}
?>

<script>
    window.onload = function() {
        let getfinaldata = localStorage.getItem("finalData");
        let parsebookdata = JSON.parse(getfinaldata);
        console.log(parsebookdata.movieName);
        let show = document.getElementById("show")
        let content = "";
        content = `
      <h1>Your Summary Panel</h1>
<div class="det11">
<h3 class="mov">Movie</h3>
<p class="nam">${parsebookdata.movieName}</p>
    </div>

    <div class="det11">
<h3 class="mov">Date</h3>
<p class="nam1"></p>
    </div>

    <div class="det11">
<h3 class="mov">Time</h3>
<p class="nam">${parsebookdata.movieTime}</p>
    </div>

    <div class="det11">
<h3 class="mov">Seats</h3>
<p class="nam1">${showSelectedSeat(parsebookdata.selectedSeat)}</p>
    </div>
      
      `
        show.innerHTML = content;
        document.getElementById("amount").value = parsebookdata.totalPrice;
        document.getElementById("amount1").value = parsebookdata.totalPrice;
        const date = new Date();
        const formattedDate = `${date.getFullYear()}/${String(date.getMonth() + 1).padStart(2, '0')}/${String(date.getDate()).padStart(2, '0')}`;
        document.querySelector('.nam1').textContent = formattedDate;
    }

    function showSelectedSeat(seats) {
        let seatList = '';
        seats.forEach(element => {
            seatList += `<li class="ele">${element}</li>`; // Append each seat as an <li>
        });
        return `${seatList}`; // Wrap the list items inside <ul>
    }
</script>