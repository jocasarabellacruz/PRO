<?php
header('Content-Type: application/json');

// Connect to the database
$conn = new mysqli("localhost", "root", "", "shelfsearchdb");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => $conn->connect_error]);
    exit();
}

// Get the input data
$data = json_decode(file_get_contents("php://input"), true);
$studentID = $data['studentID'];

// Check if the student exists in studentinfo table
$checkQuery = "SELECT * FROM studentinfo WHERE studentID = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Log the student in
    $current_time = date('Y-m-d H:i:s');
    $logQuery = "INSERT INTO studentlog (studentID, status, loggedInTime) VALUES (?, 'logged_in', ?)";
    $logStmt = $conn->prepare($logQuery);
    $logStmt->bind_param("ss", $studentID, $current_time);
    if ($logStmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $logStmt->error]);
    }
    $logStmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Student not found']);
}

$stmt->close();
$conn->close();
?>
