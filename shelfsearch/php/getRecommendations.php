<?php
include 'conn.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = intval($_POST['studentID']); // Sanitize input

    // Fetch genres of books the student has borrowed
    $borrowedGenresQuery = "
        SELECT DISTINCT b.genre
        FROM bookrecord br
        JOIN books b ON br.barcode = b.barcode
        WHERE br.studentID = '$studentID' AND br.bookstatus = 'borrowed'";

    $borrowedGenresResult = mysqli_query($conn, $borrowedGenresQuery);
    $genres = [];

    while ($row = mysqli_fetch_assoc($borrowedGenresResult)) {
        $genres[] = $row['genre'];
    }

    if (!empty($genres)) {
        // Generate recommendations based on genres
        $genreList = "'" . implode("','", $genres) . "'";
        $recommendationQuery = "
            SELECT title, author, genre, synopsis, publicationYear
            FROM books
            WHERE genre IN ($genreList) AND status = 'Available'
            ORDER BY RAND()
            LIMIT 5";

        $recommendationResult = mysqli_query($conn, $recommendationQuery);

        if (mysqli_num_rows($recommendationResult) > 0) {
            while ($row = mysqli_fetch_assoc($recommendationResult)) {
                echo "<div class='recommendation-card'>
                        <h4>" . htmlspecialchars($row['title']) . "</h4>
                        <p><strong>Author:</strong> " . htmlspecialchars($row['author']) . "</p>
                        <p><strong>Genre:</strong> " . htmlspecialchars($row['genre']) . "</p>
                        <p><strong>Year:</strong> " . htmlspecialchars($row['publicationYear']) . "</p>
                        <p><strong>Synopsis:</strong> " . htmlspecialchars($row['synopsis']) . "</p>
                      </div>";
            }
        } else {
            echo "<p>No recommendations found based on your borrowing history.</p>";
        }
    } else {
        echo "<p>No borrowing history available for recommendations.</p>";
    }
}
?>
