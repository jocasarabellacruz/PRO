<?php
include 'conn.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = intval($_POST['studentID']);

    $borrowedGenresQuery = "
        SELECT DISTINCT lower(trim(b.genre)) as genre
        FROM bookrecord br
        JOIN books b ON br.barcode = b.barcode
        WHERE br.studentID = '$studentID' AND br.bookstatus = 'borrowed'";

    $borrowedGenresResult = mysqli_query($conn, $borrowedGenresQuery);
    $genres = [];

    if (!$borrowedGenresResult) {
        echo "Error fetching genres: " . mysqli_error($conn);
        exit;
    }

    while ($row = mysqli_fetch_assoc($borrowedGenresResult)) {
        $genres[] = $row['genre'];
    }

    if (!empty($genres)) {
        $genreList = "'" . implode("','", $genres) . "'";
        $recommendationQuery = "
            SELECT title, author, genre, synopsis, publicationYear
            FROM books
            WHERE lower(trim(genre)) IN ($genreList) AND status = 'Available'
            ORDER BY RAND()
            LIMIT 5";

        $recommendationResult = mysqli_query($conn, $recommendationQuery);

        if (!$recommendationResult) {
            echo "Error fetching recommendations: " . mysqli_error($conn);
            exit;
        }

        $numResults = mysqli_num_rows($recommendationResult);

        if ($numResults > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Year</th>
                        <th>Synopsis</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($recommendationResult)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['title']) . "</td>
                        <td>" . htmlspecialchars($row['author']) . "</td>
                        <td>" . htmlspecialchars($row['genre']) . "</td>
                        <td>" . htmlspecialchars($row['publicationYear']) . "</td>
                        <td>" . htmlspecialchars($row['synopsis']) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No recommendations found based on your borrowing history.</p>";
        }
    } else {
        echo "<p>No borrowing history available for recommendations.</p>";
    }
}
?>
