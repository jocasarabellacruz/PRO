<?php
include 'conn.php'; 

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['barcode'])) {
    $barcode = $data['barcode'];
    $studentID = 202201798; 

$checkQuery = "SELECT bookstatus FROM bookrecord 
                   WHERE barcode = '$barcode' AND studentID = '$studentID' AND bookstatus = 'borrowed'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        $updateRecordQuery = "UPDATE bookrecord 
                              SET bookstatus = 'returned', dateReturned = NOW() 
                              WHERE barcode = '$barcode' AND studentID = '$studentID' AND bookstatus = 'borrowed'";
        $updateBookQuery = "UPDATE books SET status = 'Available' WHERE barcode = '$barcode'";

        if (mysqli_query($conn, $updateRecordQuery) && mysqli_query($conn, $updateBookQuery)) {
            echo json_encode(['success' => true, 'message' => 'Book returned successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to return the book.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Book is not currently borrowed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No barcode provided.']);
}
?>
