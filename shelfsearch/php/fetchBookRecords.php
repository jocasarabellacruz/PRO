<?php
include 'conn.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = intval($_POST['studentID']); 

    $query = "SELECT b.title, br.bookstatus, br.dateBorrowed, br.dateReturned
              FROM bookrecord br
              JOIN books b ON br.barcode = b.barcode
              WHERE br.studentID = $studentID";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['title']) . "</td>
                        <td>" . htmlspecialchars($row['bookstatus']) . "</td>
                        <td>" . htmlspecialchars($row['dateBorrowed'] ?? '-') . "</td>
                        <td>" . htmlspecialchars($row['dateReturned'] ?? '-') . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No records found</td></tr>";
        }
    }
}
?>
