<?php
header('Content-Type: application/json');

// Connect to the database
$conn = new mysqli("localhost", "root", "", "shelfsearchdb");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => $conn->connect_error]);
    exit();
}

// Update the logged-in student's status to 'logged_out'
$query = "
    UPDATE studentlog
    SET status = 'logged_out', loggedOutTime = NOW()
    WHERE status = 'logged_in' AND loggedOutTime IS NULL
    LIMIT 1
";
if ($conn->query($query) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();
?>
