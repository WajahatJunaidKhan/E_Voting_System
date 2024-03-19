<?php
include("connect.php");
$name = $_POST['name'];
$CNIC = $_POST['CNIC'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$address = $_POST['address'];
$image = $_FILES['photo']['name'];
$tmp_name = $_FILES['photo']['tmp_name'];
$role = $_POST['role'];

if ($password == $cpassword) {
    move_uploaded_file($tmp_name, "../uploads/$image");

    if ($role == 2) {
        $checkGroup = mysqli_query($connect, "SELECT * FROM `group` WHERE CNIC = '$CNIC' OR name = '$name'");
        if (mysqli_num_rows($checkGroup) > 0) {
            echo '
                <script>
                    alert("Name or CNIC is owned by another group. Try another.");
                    window.location = "../routes/register.html";
                </script>
            ';
        } else {
            $insertGroup = mysqli_query($connect, "INSERT INTO `group` (name,CNIC,address,password,photo,role,status,votes) VALUES ('$name','$CNIC','$address','$password','$image','$role',0,0)");

            if ($insertGroup) {
                echo '
                    <script>
                        alert("Registration Successful for role 2");
                        window.location = "../";
                    </script>
                ';
            } else {
                echo '
                    <script>
                        alert("Error occurred while inserting into group table");
                        window.location = "../routes/register.html";
                    </script>
                ';
            }
        }
    } else {
        $checkUser = mysqli_query($connect, "SELECT * FROM USER WHERE CNIC = '$CNIC'");
        if (mysqli_num_rows($checkUser) > 0) {
            echo '
                <script>
                    alert("CNIC owned by a user. Try another CNIC.");
                    window.location = "../routes/register.html";
                </script>
            ';
        } else {
            $insertUser = mysqli_query($connect, "INSERT INTO USER(name,CNIC,address,password,photo,role,status,votes) VALUES ('$name','$CNIC','$address','$password','$image','$role',0,0)");

            if ($insertUser) {
                echo '
                    <script>
                        alert("Registration Successful");
                        window.location = "../";
                    </script>
                ';
            } else {
                echo '
                    <script>
                        alert("Some error occurred!");
                        window.location = "../routes/register.html";
                    </script>
                ';
            }
        }
    }
} else {
    echo '
        <script>
            alert("Password and confirm Password do not match");
            window.location = "../routes/register.html";
        </script>
    ';
}
?> 
