<?php
include 'conn.php'; 

$query = "SELECT studentID FROM studentlog WHERE status = 'logged_in' ORDER BY LogID DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['success' => true, 'studentID' => $row['studentID']]);
} else {
    echo json_encode(['success' => false, 'message' => 'No logged-in student found.']);
}
?>
