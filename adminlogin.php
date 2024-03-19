<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Assuming you have established a database connection in connect.php
include("api/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to fetch hashed password based on the provided username
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password' ";
    
    $result = mysqli_query($connect, $query);



    if ($result) {
        // Check if a row was returned
        if (mysqli_num_rows($result) == 1) {
            // Fetch the hashed password
            $row = mysqli_fetch_assoc($result);
            $hashedPassword = $row['password'];
            echo $hashedPassword;
            // Verify the password
            if ($password== $hashedPassword) {
                // Password matches, login successful
                // Start the session or set necessary session variables for admin
                session_start();
                $_SESSION['username'] = $username;

                // Redirect to admin panel or any admin-specific page
                header("Location: records.php");
                exit();
            } else {
                // Password doesn't match
                echo "Username or password incorrect.";
            }
        } else {
            // No matching username found
            echo "Username or password incorrect.";
        }
    } else {
        // Query execution failed
        echo "Error executing query: " . mysqli_error($connect);
    }
}
?>
