<?php
include 'conn.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['barcode'])) {
    $barcode = $data['barcode'];
    $studentID = 202201798; 
    $checkBorrowLimitQuery = "SELECT COUNT(*) AS borrowedCount 
                              FROM bookrecord 
                              WHERE studentID = '$studentID' AND bookstatus = 'borrowed'";
    $borrowLimitResult = mysqli_query($conn, $checkBorrowLimitQuery);
    $borrowLimitRow = mysqli_fetch_assoc($borrowLimitResult);

    if ($borrowLimitRow['borrowedCount'] >= 3) {
        echo json_encode([
            'success' => false,
            'message' => 'You have already borrowed 3 books. Please return a book before borrowing another.'
        ]);
        exit;
    }

    $checkQuery = "SELECT status FROM books WHERE barcode = '$barcode'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        $row = mysqli_fetch_assoc($checkResult);

        if ($row['status'] === 'Available') {
            $updateBookQuery = "UPDATE books SET status = 'Borrowed' WHERE barcode = '$barcode'";
            $recordBookQuery = "INSERT INTO bookrecord (studentID, barcode, bookstatus, dateBorrowed)
                                VALUES ('$studentID', '$barcode', 'borrowed', NOW())";

            if (mysqli_query($conn, $updateBookQuery) && mysqli_query($conn, $recordBookQuery)) {
                echo json_encode(['success' => true, 'message' => 'Book borrowed successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to borrow the book.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Book is already borrowed or unavailable.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid barcode.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No barcode provided.']);
}
?>
