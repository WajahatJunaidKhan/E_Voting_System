<?php
include("api/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cname = $_POST['cname'];
    $CNIC = $_POST['CNIC'];
    $password = $_POST['password'];
    $district = $_POST['district'];
    $gid = $_POST['gid'];

    // Check if the provided gid exists in the group table
    $checkGidQuery = "SELECT gid FROM `group` WHERE gid = $gid";
    $gidResult = mysqli_query($connect, $checkGidQuery);

    if (mysqli_num_rows($gidResult) > 0) {
        $insertQuery = "INSERT INTO `candidate` (cname, CNIC, password, district, gid, vote) 
                        VALUES ('$cname', '$CNIC', '$password', '$district', $gid, 0)";

        $result = mysqli_query($connect, $insertQuery);

        if (!$result) {
            // Insertion failed, handle the error
            $errorMessage = mysqli_error($connect);
            echo '<script>alert("Error occurred while registering the candidate: ' . $errorMessage . '");</script>';
        } else {
            // Insertion successful
            echo '<script>alert("Candidate registered successfully.");</script>';
            header("Location: routes/register.html");
            exit();
        }
    } else {
        echo '<script>alert("Invalid gid provided.");</script>';
    }
}

mysqli_close($connect);
?>
