<?php
header('Content-Type: application/json');

// Connect to the database
$conn = new mysqli("localhost", "root", "", "shelfsearchdb");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => $conn->connect_error]);
    exit();
}

// Fetch the logged-in student ID
$query = "SELECT studentID FROM studentlog WHERE status = 'logged_in' AND loggedOutTime IS NULL LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentID = $row['studentID'];

    // Fetch the student's name from the studentinfo table
    $query = "SELECT CONCAT(studentFName, ' ', studentLName) AS name FROM studentinfo WHERE studentID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $studentID);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();

    if ($name) {
        echo json_encode(['success' => true, 'name' => $name]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Student not found.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No logged-in student found.']);
}

$conn->close();
?>
