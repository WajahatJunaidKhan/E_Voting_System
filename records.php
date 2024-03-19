<?php
include("api/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['table'])) {
    $table = $_GET['table'];

    if ($table === 'group' || $table === 'user') {
        // Fetch records based on the selected table
        $query = "SELECT * FROM `$table`"; // Use backticks around table name
        $result = mysqli_query($connect, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Display records in a table
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Records</title>
            </head>
            <body>
                <h2>Records</h2>
                <table border="1">
                    <thead>';

            // Table headers based on the selected table
            switch ($table) {
                case 'group':
                    echo '<tr>
                        <th>Group ID</th>
                        <th>Group Name</th>
                        <th>CNIC</th>
                        <th>Password</th>
                        <th>Address</th>
                        <th>Photo</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Votes</th>
                    </tr>';
                    break;
                case 'user':
                    echo '<tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>CNIC</th>
                        <th>Password</th>
                        <th>Address</th>
                        <th>Photo</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Votes</th>
                    </tr>';
                    break;
            }

            echo '</thead><tbody>';

            // Display each row in the result set
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                foreach ($row as $value) {
                    echo '<td>' . $value . '</td>';
                }
                echo '</tr>';
            }

            // Close the HTML table for other records
            echo '</tbody></table></body></html>';

            // Free the result set
            mysqli_free_result($result);
        } else {
            // No records found or query failed
            echo "No records found.";
        }
    } elseif ($table === 'votecast') {
        // Fetch vote count records from votecast table
        $voteCountQuery = "SELECT gname, COUNT(*) AS total_votes
                           FROM votecast 
                           WHERE votestatus = 1
                           GROUP BY gname";
        $voteCountResult = mysqli_query($connect, $voteCountQuery);

        if ($voteCountResult && mysqli_num_rows($voteCountResult) > 0) {
            // Display vote count records in a table
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Vote Count</title>
            </head>
            <body>
                <h2>Vote Count</h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Total Votes</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = mysqli_fetch_assoc($voteCountResult)) {
                echo '<tr>';
                echo '<td>' . $row['gname'] . '</td>';
                echo '<td>' . $row['total_votes'] . '</td>';
                echo '</tr>';
            }

            // Close the HTML table for vote count
            echo '</tbody></table></body></html>';

            mysqli_free_result($voteCountResult);
        } else {
            echo "No votes found.";
        }

        // Display the contents of the votecast table
        $votecastQuery = "SELECT * FROM votecast";
        $votecastResult = mysqli_query($connect, $votecastQuery);

        if ($votecastResult && mysqli_num_rows($votecastResult) > 0) {
            // Display votecast records in a table
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Votecast Records</title>
            </head>
            <body>
                <h2>Votecast Records</h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Cast ID</th>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>Group ID</th>
                            <th>Group Name</th>
                            <th>VoteStatus</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = mysqli_fetch_assoc($votecastResult)) {
                echo '<tr>';
                foreach ($row as $value) {
                    echo '<td>' . $value . '</td>';
                }
                echo '</tr>';
            }

            // Close the HTML table for votecast records
            echo '</tbody></table></body></html>';

            mysqli_free_result($votecastResult);
        } else {
            echo "No votecast records found.";
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $tableToDelete = $_POST['tableToDelete']; // Get the table name
    $cnic = $_POST['cnic'];
    $name = $_POST['name'];

    // Check if the table is either 'group' or 'user'
    if ($tableToDelete === 'group' || $tableToDelete === 'user') {
        // Formulate the query to delete based on CNIC and name
        $deleteQuery = "DELETE FROM `$tableToDelete` WHERE CNIC = '$cnic' AND name = '$name'";
        $deleteResult = mysqli_query($connect, $deleteQuery);

        if ($deleteResult) {
            echo '<script>alert("Record deleted successfully.");</script>';
        } else {
            echo '<script>alert("Error occurred while deleting the record.");</script>';
        }
    } else {
        echo '<script>alert("Invalid table name.");</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Records</title>
    <link rel="stylesheet" href="CSS/stylesheet.css">
</head>
<body style="background-color: #776BCC; color: white;">
    <center>
    <h2>View Records</h2>
    <form action="" method="GET">
        <button style="padding: 10px;
                            border-radius: 5px;
                            background-color: rgb(0, 157, 255);
                            color: white;" type="submit" name="table" value="group">Show Group Records</button>
        <button style="padding: 10px;
                            border-radius: 5px;
                            background-color: rgb(0, 157, 255);
                            color: white;"  type="submit" name="table" value="user">Show User Records</button>
        <button style="padding: 10px;
                            border-radius: 5px;
                            background-color: rgb(0, 157, 255);
                            color: white;" type="submit" name="table" value="votecast">Show Vote Count</button>
    </form>
    
    <form action="" method="POST">
        <h2>Delete Record</h2>
        <label for="tableToDelete">Select Table:</label>
        <select name="tableToDelete" id="tableToDelete">
            <option value="group">Group</option>
            <option value="user">User</option>
        </select>
        <br><br>
        <label for="cnic">CNIC:</label>
        <input type="text" name="cnic" id="cnic">
        <br><br>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name">
        <br><br>
        <button style="padding: 10px;
                            border-radius: 5px;
                            background-color: rgb(0, 157, 255);
                            color: white;" type="submit" name="delete">Delete Record</button><br><br>
    </form>
    <form action="routes/logout.php" method="POST">
        <button style="padding: 10px;
                        border-radius: 5px;
                        background-color: rgb(255, 0, 0);
                        color: white;" type="submit" name="logout">Logout</button>
    </form>
    </center>
</body>
</html>
