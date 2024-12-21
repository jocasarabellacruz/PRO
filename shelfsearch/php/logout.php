<?php
include 'conn.php'; 

$query = "UPDATE studentlog SET status = 'logged_out', loggedOutTime = NOW() 
          WHERE status = 'logged_in'";

if (mysqli_query($conn, $query)) {
    http_response_code(200); 
    session_start();
    session_destroy(); 
} else {
    http_response_code(500); 
    echo "Error logging out: " . mysqli_error($conn);
}
?>
