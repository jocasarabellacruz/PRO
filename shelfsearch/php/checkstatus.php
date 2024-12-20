<?php
require_once 'conn.php'; // Ensure this file connects to your database

header('Content-Type: application/json');

$sql = "SELECT studentID, status FROM studentlog WHERE status = 'logged_in' ORDER BY loggedInTime DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'studentID' => $row['studentID'],
        'status' => $row['status']
    ]);
} else {
    echo json_encode(['status' => 'no_login']);
}

$conn->close();
?>
