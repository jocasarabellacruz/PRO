<?php
include 'php/conn.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = $_POST['studentID']; // Get student ID from the form or request
    $barcode = $_POST['barcode']; // Get book barcode from the form or request

    // Update the record in the bookrecord table
    $query = "UPDATE bookrecord 
              SET bookstatus = 'returned', dateReturned = NOW() 
              WHERE studentID = $studentID AND barcode = $barcode AND bookstatus = 'borrowed'";

    if (mysqli_query($conn, $query)) {
        // Update the book's status to available in the books table
        $updateQuery = "UPDATE books SET status = 'available' WHERE barcode = $barcode";
        if (mysqli_query($conn, $updateQuery)) {
            echo "Book successfully returned!";
        } else {
            echo "Error updating book status: " . mysqli_error($conn);
        }
    } else {
        echo "Error updating book record: " . mysqli_error($conn);
    }
}
?>
