<?php 
session_start();
include("connect.php");

$CNIC = $_POST['CNIC'];
$password = $_POST['password'];
$role = $_POST['role'];

if ($role == 2) { // Group login
    echo '
        <script>
            alert("Groups cannot directly login. Please register as a group.");
            window.location = "../routes/register.html";
        </script>
    ';
    exit(); // Stop further execution
} else { // User login
    $check = mysqli_query($connect, "SELECT * FROM user WHERE CNIC='$CNIC' AND password='$password' AND role='$role'");
    if (mysqli_num_rows($check) > 0) {
        $userdata = mysqli_fetch_array($check);
        $_SESSION['userdata'] = $userdata;
        header("location: ../routes/dashboard.php");
        exit();
    } else {
        echo '
            <script>
                alert("Invalid Credentials, user not found!");
                window.location = "../routes/register.html";
            </script>
        ';
    }
}
?>
