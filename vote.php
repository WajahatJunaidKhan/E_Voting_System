<?php
session_start();
include("connect.php");

$votes = $_POST['gvotes'];
$total_votes = $votes + 1;
$gid = $_POST['gid'];
$uid = $_SESSION['userdata']['id'];
$uname = $_SESSION['userdata']['name'];
$cid = $_POST['candidate'];

$update_votes = mysqli_query($connect, "UPDATE user SET votes='$total_votes' WHERE id='$gid'");
$update_user_status = mysqli_query($connect, "UPDATE user SET status = 1 WHERE id = '$uid'");

// Retrieve the current vote count for a candidate with ID $cid
$vote = mysqli_query($connect, "SELECT vote FROM candidate WHERE candidate_id = '$cid'");
$vote_data = mysqli_fetch_assoc($vote);

if ($vote_data) {
    $current_vote_count = $vote_data['vote'];
    
    // Update the vote count by incrementing it by one for a candidate with ID $uid
    $update_vote_status = mysqli_query($connect, "UPDATE candidate SET vote = vote + 1 WHERE candidate_id = '$cid'");
    
    if ($update_vote_status) {
        // Update was successful
        echo "Vote count updated successfully!";
    } else {
        // Error in the update query
        echo "Error updating vote count: " . mysqli_error($connect);
    }
} else {
    $update_vote_status = mysqli_query($connect, "UPDATE candidate SET vote = 1 WHERE candidate_id = '$cid'");
    echo "Candidate not found!";
}


$update_candidate_status = mysqli_query($connect, "UPDATE user SET status = 1 WHERE id = '$uid'");

if ($update_votes && $update_user_status) {
    $insertVoteCast = mysqli_query($connect, "INSERT INTO votecast (uid, uname, gid, gname, votestatus) VALUES ('$uid', '$uname', '$gid', (SELECT name FROM `group` WHERE gid = '$gid'), 1)");

    if ($insertVoteCast) {
        $groups = mysqli_query($connect, "SELECT * FROM user WHERE role = 2");
        $groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);

        $_SESSION['userdata']['status'] = 1;
        $_SESSION['groupsdata'] = $groupsdata;

        echo '
            <script>
                alert("Voting Successful");
                window.location = "../routes/dashboard.php";
            </script>
        ';
    } else {
        echo '
            <script>
                alert("Error occurred while inserting into votecast table");
                window.location = "../routes/dashboard.php";
            </script>
        ';
    }
} else {
    echo '
        <script>
            alert("Some error occurred!");
            window.location = "../routes/dashboard.php";
        </script>
    ';
}
?>
