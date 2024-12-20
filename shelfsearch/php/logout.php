<?php
include 'conn.php'; // Include the database connection

// Update the logged-in user's status to 'logged_out'
$query = "UPDATE studentlog SET status = 'logged_out', loggedOutTime = NOW() 
          WHERE status = 'logged_in'";

if (mysqli_query($conn, $query)) {
    // Send a success response
    http_response_code(200); // OK
    session_start();
    session_destroy(); // Destroy the session
} else {
    // Send an error response
    http_response_code(500); // Internal Server Error
    echo "Error logging out: " . mysqli_error($conn);
}
?>
