<?php
	$servername = "localhost";
    $username = "abduaza1_admin";
    $password = "d=aI6!iz)w:#";
    $dbname = "abduaza1_dasturxon";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, "utf8mb4");
    if (!$conn) {
        echo "Database connection failed";
    } else {
        echo "Databse connection successed";
    }
?>