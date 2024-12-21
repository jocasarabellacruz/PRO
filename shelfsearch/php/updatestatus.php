<?php
include('conn.php');

$studentID = $_GET['id']; 

$sql = "SELECT * FROM students WHERE studentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    
    if ($student['status'] === 'logged_in') {
        echo json_encode(["success" => false, "message" => "Student is already logged in."]);
    } else {
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
