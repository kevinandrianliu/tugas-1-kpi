<?php
    $servername = "localhost";
    $username = "root";
    # $password = "password";

    $user = $_GET["usr"];
    $book = $_GET["id"];
    $quantity = $_GET["q"];
    $date = 

    $query = "INSERT INTO transaction (book_id, username, amount, date_bought) VALUES ($book, \"$user\", $quantity, CURRENT_DATE())";
    $conn = new mysqli($servername, $username, "", "wbd_schema");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($conn->query($query) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
    $conn->close();
?>