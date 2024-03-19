<?php
session_start();
if (!isset($_SESSION['userdata'])) {
    header("location: ../");
}

$userdata = $_SESSION["userdata"];

// Check the user's voting status
if ($_SESSION['userdata']['status'] == 0) {
    $status = '<b style="color: red"> Not voted </b>';
} else {
    $status = '<b style="color: green"> Voted </b>';
}

include("../api/connect.php"); // Include your database connection

// Fetch data from the 'group' table
$query = mysqli_query($connect, "SELECT * FROM `group`");
if ($query) {
    $groupsdata = mysqli_fetch_all($query, MYSQLI_ASSOC);
}

if (!empty($userdata['address'])) {
    $addressDistrict = $userdata['address'];
    $candidateQuery = mysqli_query($connect, "SELECT * FROM `candidate` WHERE district = '$addressDistrict'");
    if ($candidateQuery) {
        $resultArray = array(); 
        while ($candidateData = mysqli_fetch_assoc($candidateQuery)){
            if ($candidateData) {
                $candidateGroupId = $candidateData['gid'];
                
                // Fetch group data for the matched group ID
                $matchedGroupQuery = mysqli_query($connect, "SELECT * FROM `group` WHERE gid = '$candidateGroupId'");

                if ($matchedGroupQuery) {
                    $matchedGroup = mysqli_fetch_assoc($matchedGroupQuery);

                    // Store matchedGroup data in resultArray
                    $resultArray[] = array(
                        'candidate_data' => $candidateData,
                        'group_data' => $matchedGroup
                    );
                }
            }
        }
    }
}




?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voting System - Dashboard</title>
    <link rel="stylesheet" href="../CSS/stylesheet.css">
</head>
<body style="background-color: #776BCC;">
    <style>
           #backbtn{
            padding: 10px;
            border-radius: 5px;
            background-color: rgb(0, 157, 255);
            color: white;
            float: left;
            margin: 10px;
           } 

           #logoutbtn{
            padding: 10px;
            border-radius: 5px;
            background-color: rgb(0, 157, 255);
            color: white;
            float: right;
            margin: 10px;
           } 

           #Profile{
            background-color: #55d4db;
            width: 30%;
            padding: 20px;
            float: left;
           }

           #Group{
            background-color: #55d4db;
            width: 60%;
            padding: 20px;
            float: right;
           }

           #votebtn{
            padding: 5px;
            font-size: 15px;
            border-radius: 5px;
            background-color: #3498db;
            color: white;
           }

           #mainpanel{
            padding: 10px;
           }

           #voted{
            padding: 5px;
            font-size: 15px;
            border-radius: 5px;
            background-color: yellow;
            color: white;
           }
           
        </style>    

        <div id="mainSection">
            <center>
            <div id="headerSection">
                <a href="../"><button id="backbtn">BACK</button></a>
                <a href="logout.php"><button id="logoutbtn">LOG OUT</button></a> 
                <h1>E-Voting System</h1>
            </div>
            </center>
            <hr>
            <div id="mainpanel">
            <div id = "Profile">
                <center><img src="../uploads/<?php echo $userdata['photo']?>" height="100" width="100"><br><br></center>
                <b>Name:</b><?php echo $userdata['name']?><br><br>
                <b>CNIC:</b><?php echo $userdata['CNIC']?><br><br>
                <b>address:</b><?php echo $userdata['address']?><br><br>
                <b>Status:</b><?php echo $status?><br><br>
            </div>
            <div id = "Group">
            <?php
                function hello(){
                    echo "hello";
                }
                if (!empty($resultArray)) {
                    foreach ($resultArray as $result) {
                        $group = $result['group_data'];
                        $candidate = $result['candidate_data']['candidate_id'];
                        ?>
                        <div>
                        <img style="float: right" src="../uploads/<?php echo $group['photo'] ?>" height="100" width="100"><br>
                            <b>Group Name: </b><?php echo $group['name'] ?><br><br>
                            <b>Votes: </b><?php echo $group['votes'] ?><br><br>
                            <?php if ($_SESSION['userdata']['role'] == 2) { ?>
                            <b style="color: red">Can't Vote</b>
                            <?php } else { ?>
                            <form action="../api/vote.php" method="POST">
                            <input type="hidden" name="gvotes" value="<?php echo $group['votes'] ?>">
                            <input type="hidden" name="gid" value="<?php echo $group['gid'] ?>">
                            <input type="hidden" name="candidate" value="<?php echo $candidate ?>">
                            <?php if ($_SESSION['userdata']['status'] == 0) { ?>
                            <button type="submit" onclick = "hello()" name="votebtn" value="Vote" id="votebtn">Vote</button>
                            <?php } else { ?>
                            <button disabled type="button" name="votebtn" value="Vote" id="voted">Voted</button>
                            <?php } ?>
                            </form>
                            <?php } ?>     
                        </div>
                        <hr>
                        <?php
                    }
                } else {
                    echo "No group data available";
                }
                ?>
            </div>
            </div>
        </div>
        </body>
</html>