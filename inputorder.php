<?php
    $servername = "localhost";
    $username = "root";
    # $password = "password";

    $user = $_GET["usr"];
    $book = $_GET["id"];
    $quantity = $_GET["q"];

    $conn = new mysqli($servername, $username, "", "wbd_schema");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "INSERT INTO transaction (book_id, username, amount, date_bought) VALUES (?, ?, ?, CURRENT_DATE())";
    $stmt = $conn->prepare($query)
    $stmt->bind_param("isi",$book,$user,$quantity)
    
    if ($stmt->execute() === TRUE) {

        $query2 = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($query2);
        $id = $result->fetch_assoc();
        echo "<h4>Pemesanan Berhasil!</h4> <p>Nomor transaksi:" . $id["LAST_INSERT_ID()"] . "</p>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
    $conn->close();
?>