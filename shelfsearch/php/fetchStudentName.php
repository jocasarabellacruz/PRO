<?php
include 'conn.php';

$query = "SELECT s.studentFName, s.studentLName 
          FROM studentlog l 
          JOIN studentinfo s ON l.studentID = s.studentID 
          WHERE l.status = 'logged_in' 
          LIMIT 1";

$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    echo htmlspecialchars($row['studentFName'] . " " . $row['studentLName']);
} else {
    echo "Student";
}
?>
