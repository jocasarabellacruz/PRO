<?php
include 'php/conn.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = $_POST['studentID']; // Get student ID from the form or request
    $barcode = $_POST['barcode']; // Get book barcode from the form or request

    // Insert a new record into the bookrecord table
    $query = "INSERT INTO bookrecord (studentID, barcode, bookstatus, dateBorrowed) 
              VALUES ($studentID, $barcode, 'borrowed', NOW())";

    if (mysqli_query($conn, $query)) {
        // Update the book's status to borrowed in the books table
        $updateQuery = "UPDATE books SET status = 'borrowed' WHERE barcode = $barcode";
        if (mysqli_query($conn, $updateQuery)) {
            echo "Book successfully borrowed!";
        } else {
            echo "Error updating book status: " . mysqli_error($conn);
        }
    } else {
        echo "Error adding book record: " . mysqli_error($conn);
    }
}
?>
