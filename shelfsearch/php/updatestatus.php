<?php
// scan-id.php: Handle the scanned ID and update status
include('conn.php');

$studentID = $_GET['id'];  // Get the scanned student ID

// Check if student exists in the database
$sql = "SELECT * FROM students WHERE studentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    
    // Check if student is already logged in
    if ($student['status'] === 'logged_in') {
        echo json_encode(["success" => false, "message" => "Student is already logged in."]);
    } else {
        // Update the student's status to logged_in and set loggedInTime
        $loggedInTime = date('Y-m-d H:i:s');
        $sql_update = "UPDATE students SET status = 'logged_in', loggedInTime = ? WHERE studentID = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $loggedInTime, $studentID);
        $stmt_update->execute();

        echo json_encode(["success" => true, "message" => "Student logged in successfully."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Student ID not found."]);
}

$stmt->close();
$conn->close();
?>
